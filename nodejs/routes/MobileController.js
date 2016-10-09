exports.MobileController = function(app,$, model){
    app.get('/mobtraffic/advertising/:websiteId/:params', function(req, res){
        var date = new Date();
        req.params.params = req.params.params
                                    .replace(/-7B/g, '{')
                                    .replace(/-22/g, '"')
                                    .replace(/-7D/g, '}')
                                    .replace(/%7B/g, '{')
                                    .replace(/%22/g, '"')
                                    .replace(/%7D/g, '}');
        console.time('reportinc_update');
        console.time('delivery');
		console.log($._.chain(req.cookies.fc).filter(function(num){ return num.banned==1; }).pluck("campaignId").map(function(num){return parseInt(num); }).value())
        model.Adspaces.findOne({adspaceId:parseInt(req.params.websiteId),status:1,globalTypeId:"mobile", website:{$in:[null,JSON.parse(req.params.params).Site]}}).exec(function(err,AdspaceDB){
            if($.count(AdspaceDB)==0){
                res.end();
                console.log( "false website" );
            }else{ 
                var ip = $.ip2Long(req.connection.remoteAddress);
                model.Geolocation.findOne({startIp:{$lte: ip},endIp:{$gte: ip}}).cache().exec(function(err,GeolocationDB){
                    $.mobileAdTypeFilter({
                        InterestitialInterval	: AdspaceDB.InterestitialInterval,
                        PopAdInterval    		: AdspaceDB.PopAdInterval,
                        BannerInterval    	    : AdspaceDB.BannerInterval,
                        types					: AdspaceDB.typeId,
                        req						: req},
                        function(types){
                            if(!$.empty(req.session.none_session_var) && req.session.none_session_var==1){
                                req.session.none_session_var=0;
                            }
                            var parsed          = $.ua_parser.parse(req.headers['user-agent']);
                            var mobileDetection = new $.md(req.headers['user-agent']);

                            model.Campaigns.find({
                                globalTypeId:"mobile",status:1,showPlace:AdspaceDB.showPlace,
                                typeId:{$in:types},
                                blockedAdspaces:{$nin:[parseInt(req.params.websiteId)]},
                                geoTargeting: {$in:[null,!$.empty(GeolocationDB)?GeolocationDB.countryCode:null]},
                                categoryTargeting:{ $in:[null, AdspaceDB.categoryId]},
                                "$where": "this.maxBid < this.budget",
                                osTargeting:{$in:[null,parsed.os.family]},
                                deviceTypeTargeting:{$in:[null,((mobileDetection.tablet()===null)?((mobileDetection.phone()===null)?false:'phone'):'tablet')]},
                                browserTargeting:{$in:[null,parsed.userAgent.family]},
                                "$and":[
                                    { '$or':[{
                                            platformTargeting: null
                                        },{
                                            platformTargeting: { '$elemMatch': { family: parsed.os.family, major: parseInt(parsed.os.major), minor: parseInt(parsed.os.minor) } }
                                        }]
                                    },
                                    {"$or":[{
                                            timeTargeting:null
                                        },{
                                            timeTargeting:{
                                                "$elemMatch":{
                                                    timeStart : {"$lte":parseFloat(JSON.parse(req.params.params).Time)},
                                                    timeEnd : {"$gte":parseFloat(JSON.parse(req.params.params).Time)}
                                                }
                                            }
                                        }
                                    ]},
                                    { '$or':[ {
                                        CPCorCPM: 'CPM',maxBid: { '$gte': (($.array_key_exists("minBidCPM",GeolocationDB) && GeolocationDB.minBidCPM>0)?GeolocationDB.minBidCPM:$.Conf['minBidCPM']) }
                                        },{
                                        CPCorCPM: 'CPC',clickBid: { '$gte': (($.array_key_exists("minBidCPC",GeolocationDB) && GeolocationDB.minBidCPC>0)?GeolocationDB.minBidCPC:$.Conf['minBidCPC']) }
                                        } ]
                                    },
                                ],
                                campaignId: {
                                    "$not": {
                                        "$in":$._.chain(req.cookies.mfc).filter(function(num){ return num.banned==1; }).pluck("campaignId").map(function(num){return parseInt(num); }).value()
                                    }
                                }
                            },{
                                campaignId         :1,
                                userId             :1,
                                url                 :1,
                                maxBid              :1,
                                clickBid            :1,
                                frequencyUnit       :1,
                                frequencyQuantity   :1,
                                typeId              :1,
                                shaping             :1,
                                image               :1,
                                imageOrLanding      :1,
                                isClickAd           :1,
                                CPCorCPM            :1,
                                isFixedBid          :1,
                                iframe              :1,
                                campaignType        :1,
                                tagTypeId           :1
                            }).exec(function(err,result){
                                if($.count(result)>0){
                                $.Competition(
                                    result,
                                    {
                                        minBidCPM:((!$.empty(GeolocationDB))?GeolocationDB.minBidCPM:$.Conf['minBidCPM']),
                                        MinIntegralPayCPM:$.Conf['MinIntegralPayCPM'],
                                        minBidCPC:((!$.empty(GeolocationDB))?GeolocationDB.minBidCPC:$.Conf['minBidCPC']),
                                        MinIntegralPayCPC:$.Conf['MinIntegralPayCPC']
                                    },
                                function(key,price){
                                    var token = $.generateUniqueId();
                                    console.log("delivery");

                                    $.fcCompaign(req,{
                                        campaignId          : result[key].campaignId,
                                        frequencyUnit       : result[key]['frequencyUnit'],
                                        frequencyQuantity   : result[key]['frequencyQuantity']
                                    },function(fc){
                                        res.cookie('mfc', fc, { maxAge: 2592000000, httpOnly: true });
                                    });

                                    model.Reports.findOneAndUpdate({
                                        campaignId      : result[key]['campaignId'],
                                        adspaceId       : AdspaceDB['adspaceId'],
                                        advertiserId    : result[key]['userId'],
                                        publisherId     : AdspaceDB['userId'],
                                        globalType      : "mobile",
                                        type            : result[key]['typeId'],
                                        showPlace       : 2,
                                        CPCorCPM        : result[key]['CPCorCPM'],
                                        date            : (date.getFullYear()+"-"+(((date.getMonth()+1)<=9)?"0"+(date.getMonth()+1):(date.getMonth()+1))+"-"+((date.getDate()<=9)?"0"+date.getDate():date.getDate())),
                                        geo             : !$.empty(GeolocationDB)?GeolocationDB.countryCode:null,
                                        deviceType      : ((mobileDetection.tablet()===null)?((mobileDetection.phone()===null)?false:'phone'):'tablet'),
                                        deviceModel     : parsed.device.family,
                                        os              : parsed.os.family,
                                        osMajor         : parsed.os.major,
                                        osMinor         : parsed.os.minor,
                                        browser         : parsed.userAgent.family,
                                        editing         : 1
                                        },{$inc: {
                                            conversion      :+0,
                                            price           :+(result[key]['CPCorCPM']==='CPM'?result[key]['isClickAd']==1?0:price:0),
                                            impression      :+(result[key]['isClickAd']==1?0:1),
                                            clickImpression :+0
                                        },$set: {
                                            commission          : AdspaceDB['commission'],
                                            advertiserShaping   : result[key]['shaping'],
                                            publisherShaping    : AdspaceDB['shaping'],
                                            dateISO             : Date.now()
                                        }},{upsert : true, multi : false, new : true},function(err,docs){
                                            $.createTargetUrl({
                                                targetUrl:result[key]['url'],
                                                token:docs._id,
                                                remoteAddress:req.connection.remoteAddress,
                                                websiteId:req.params.websiteId,
                                                deviceType:((mobileDetection.tablet()===null)?((mobileDetection.phone()===null)?false:'phone'):'tablet'),
                                                os:parsed.os.family,
                                                geo:!$.empty(GeolocationDB)?GeolocationDB.countryCode:null,
                                                model: parsed.device.family
                                                },function(targetUrl){
                                                    console.timeEnd('reportinc_update');
                                                    if(!err){
                                                        var params = {
                                                            createdUrl          		: $.Conf['node_host']+"/mobtraffic/cpccount?statid="+docs._id+"&token="+token+"&cmgid="+result[key]['_id']+"&cmsid="+result[key]['campaignId']+"&cpcad="+result[key]['isClickAd'], //(result[key]['CPCorCPM']=='CPM' && result[key]['isClickAd']!=1)?targetUrl:
                                                            url                 		: targetUrl,
                                                            domain              		: $.Conf['damainName'],
                                                            node_host           		: $.Conf['node_host'],
                                                            imgDir              		: $.Conf['damainName']+$.Conf['imageUrl']+"/"+result[key]['image'],
                                                            campaignId         		    : result[key]['campaignId'],
                                                            adspaceId          		    : AdspaceDB['adspaceId'],
                                                            InterestitialInterval	    : AdspaceDB.InterestitialInterval,
                                                            PopAdInterval    	    	: AdspaceDB.PopAdInterval,
                                                            BannerInterval             	: AdspaceDB.BannerInterval,
                                                            type                		: result[key].typeId,
                                                            globalTypeId        		: result[key].globalTypeId,
                                                            imageOrLanding      		: result[key].imageOrLanding,
                                                            iframe                      : result[key].iframe,
                                                            campaignType                : result[key].campaignType,
                                                            tagTypeId                   : result[key].tagTypeId
                                                        };

                                                        res.end(" var params="+JSON.stringify(params)+"; require('"+$.Conf['damainName']+"/system/js/mobile/"+result[key]['typeId']+".js',function(){ });");
                                                        console.timeEnd('delivery');
                                                        var dataSave = model.TmpClick({
                                                            expireTime:Date.now()+1000*60*10,
                                                            price:(result[key]['CPCorCPM']==='CPC' || result[key]['isClickAd']==1?price:0),
                                                            token:token
                                                        });
                                                        dataSave.save(function(err,session){
                                                        });
                                                    }else{
                                                        res.end('potential traffic');
                                                        console.log("-");
                                                    }
                                                    model.Campaigns.findOneAndUpdate({_id:result[key]['_id']},{$inc: {budget:-(result[key]['CPCorCPM']==='CPM'?result[key]['isClickAd']==1?0:price:0)}},function(err,result){
                                                    });
                                                }
                                            );
                                        });
                                    });
                                }else{
                                    $.fcCompaign(req,{
                                        campaignId          : null,
                                        frequencyUnit       : null,
                                        frequencyQuantity   : null
                                    },function(fc){
                                        res.cookie('mfc', fc, { maxAge: 2592000000, httpOnly: true });
                                    });

                                    res.writeHead(300, {"Content-Type": "text/javascript"});

                                    var fallback = AdspaceDB.fallBack;
                                    if(fallback != null){
                                        if(fallback.match(/<script/g) != null) {
                                            if(fallback.match(/src=/g) != null){
                                                var re = /\ssrc="([^"]*)"/i;
                                                var src = fallback.match(re)[1];
                                                res.end('eval((function(){require("'+ src +'", function(){})})())');
                                            } else {
                                                fallback = fallback.replace(/<script>/g, '').replace(/<\/script>/g, '');
                                                res.end('eval((function(){'+ fallback +'})())');
                                            }
                                        } else {
                                            res.end();
                                        }
                                    } else {
                                        res.end();
                                    }

                                    console.log("--------------------------");
                                    model.PotentialReports.findOneAndUpdate({
                                        adspaceId      :AdspaceDB['adspaceId'],
                                        publisherId     :AdspaceDB['userId'],
                                        availableTypes  :AdspaceDB['typeId'],
                                        showPlace       :2,
                                        date            :(date.getFullYear()+"-"+(((date.getMonth()+1)<=9)?"0"+(date.getMonth()+1):(date.getMonth()+1))+"-"+((date.getDate()<=9)?"0"+date.getDate():date.getDate())),
                                        geo             :!$.empty(GeolocationDB)?GeolocationDB.countryCode:null,
                                        deviceType      :((mobileDetection.tablet()===null)?((mobileDetection.phone()===null)?false:'phone'):'tablet'),
                                        os              :parsed.os.family,
                                        osMajor         :parsed.os.major,
                                        osMinor         :parsed.os.minor,
                                        browser         :parsed.userAgent.family,
                                        editing         :1
                                    },{$inc:{
                                        impression      :+1
                                    },$set:{
                                        commission:AdspaceDB['commission'],
                                        publisherShaping:AdspaceDB['shaping'],
                                        dateISO:Date.now()
                                    }},{upsert : true, multi : false},function(err,doc){
                                        if(((mobileDetection.tablet()===null)?((mobileDetection.phone()===null)?false:'phone'):'tablet')){
                                            var userAgentModel = model.UserAgents({
                                                ua:req.headers['user-agent'],
                                                editing:1
                                            });
                                            userAgentModel.save(function(err,session){});
                                        }
                                    });
                                }
                            });
                        }
                    );
                });
            }
        });
    });

    app.get('/mobtraffic/interestitalskip', function(req, res){
		req.session.interestital_skip = 1;
		res.writeHead(200, {"Content-Type": "text/javascript"});
		res.end();
    });

    app.get('/mobtraffic/interestital/:url/:redirection', function(req, res){
		req.session.none_session_var = 1;
        console.log(req.params);
        res.render('mobile/interstitial',{
            redirection:req.params.redirection,
            url:req.params.url
        });
    });

    app.get('/mobtraffic/mobilepopad', function(req, res){
        res.render('mobile/appmobile',{
            image:req.query.image,
            url:req.query.url
        });
    });

    app.get('/mobtraffic/cpccount', function(req, res){
        model.TmpClick.findOne({token:req.query.token}).exec(function(err,doc){
            if($.count(doc)>0){
                model.CallBacks.findOneAndUpdate({
                    objId:req.query.statid,
                    type:req.query.cpcad==1?'impression':'click'
                },{
                    $inc:{
                        price:+doc.price,
                        impression:+1
                    }
                },{upsert:true,multi:false},function(err,docs){
                    console.log(err)
                    model.Campaigns.findOneAndUpdate({_id:req.query.cmgid},{$inc: {budget:-parseFloat(doc.price)}},function(err,result){
                        console.log(err)
                        res.cookie("conv"+req.query.cmsid,req.query.statid, { maxAge: 1000*60*60*24, httpOnly: true });
                        model.TmpClick.remove({_id:doc._id},function(err,result){
                            console.log(err)
                        });
			res.render('mobile/redirect',{
				redirection:req.query.url
			});
                    });
                });
            }else{
                res.render('mobile/redirect',{
			redirection:req.query.url
		});
            }
        });
    }); 
}