exports.WebbanerController = function(app,$, model){
    app.get('/webbaner/advertising/:websiteId/:params/:options', function(req, res){
        var date = new Date();
        req.params.params = req.params.params
                                .replace(/-7B/g, '{')
                                .replace(/-22/g, '"')
                                .replace(/-7D/g, '}')
                                .replace(/%7B/g, '{')
                                .replace(/%22/g, '"')
                                .replace(/%7D/g, '}');
        req.params.options = req.params.options
                                .replace(/-7B/g, '{')
                                .replace(/-22/g, '"')
                                .replace(/-7D/g, '}')
                                .replace(/%7B/g, '{')
                                .replace(/%22/g, '"')
                                .replace(/%7D/g, '}');
		//console.log({status:1,adspaceId:parseInt(req.params.websiteId),globalTypeId:"web", website:JSON.parse(req.params.params).Site});
		console.log(req.params);
		console.log($.ua_parser.parse(req.headers['user-agent']).userAgent.family);
        var options = JSON.parse(req.params.options);
        console.time('test');
        
        model.Adspaces.findOne({status:1,adspaceId:parseInt(req.params.websiteId),globalTypeId:"web", website:JSON.parse(req.params.params).Site},{
            adspaceId:1,
            categoryId:1,
            userId:1,
            showPlace:1,
            typeId:1,
            commission:1,
            shaping:1,
            webDesktopAdData:1

        }).exec(function(err,AdspaceDB){//.cache()
            if($.count(AdspaceDB)==0){
                res.end();
                console.log("not found website");
            } else {
                var ip = $.ip2Long(req.connection.remoteAddress);
                model.Geolocation.findOne({startIp:{$lte: ip},endIp:{$gte: ip}}).cache().exec(function(err,GeolocationDB){
                    var parsed = $.ua_parser.parse(req.headers['user-agent']);
                    var adsize = $._.chain(AdspaceDB.webDesktopAdData).filter(function(item){
                            if(options[item.desktopAdId] === undefined){
                                return false;
                            } else {
                                return $.checkAdPosition(item.desktopAdPosition, options[item.desktopAdId]) && !options[item.desktopAdId].ishide;
                            }
                        }).map(function(item){
                            return item.desktopAdSize;
                        }).value();
                    var webDesktopAdType    = JSON.parse(req.params.params).Type == 'mobile' ? [null, 'webBanner'] : [null, 'webBanner', 'webFlash'];
                    var parsed              = $.ua_parser.parse(req.headers['user-agent']);
                    console.log(parsed.os.family);
                    var  os                 = $.osParser(req.headers['user-agent']);

                    console.log({
                        globalTypeId: "web",
                        status: 1,
                        showPlace: 1,
                        blockedAdspaces: {$nin: [parseInt(req.params.websiteId)]},
                        CPCorCPM: 'CPM',
                        maxBid: {'$gte': !$.empty(GeolocationDB) ? GeolocationDB.minBidCPM : $.Conf['minBidCPM']},
                        geoTargeting: {$in: [null, !$.empty(GeolocationDB) ? GeolocationDB.countryCode : null]},
                        categoryTargeting: {$in: [null, AdspaceDB.categoryId]},
                        "$where": "this.maxBid < this.budget",
                        osTargeting: {$in: [null, os]},
                        browserTargeting: {$in: [null, parsed.userAgent.family]},
                        imageSizes: {$in: adsize},
                        webDesktopAdType: {$in: webDesktopAdType},
                        "$and": [
                            {
                                "$or": [{
                                    timeTargeting: null
                                }, {
                                    timeTargeting: {
                                        "$elemMatch": {
                                            timeStart: {"$lte": parseFloat(JSON.parse(req.params.params).Time)},
                                            timeEnd: {"$gte": parseFloat(JSON.parse(req.params.params).Time)}
                                        }
                                    }
                                }
                                ]
                            }
                        ],
                        campaignId: {
                            "$not": {
                                "$in": $._.chain(req.cookies.wfc).filter(function (num) {
                                    return num.banned == 1;
                                }).pluck("campaignId").map(function (num) {
                                    return parseInt(num);
                                }).value()
                            }
                        }
                    })

                    model.Campaigns.find({
                        globalTypeId: "web",
                        status:1,
                        showPlace:1,
                        blockedAdspaces:{$nin:[parseInt(req.params.websiteId)]},
                        CPCorCPM: 'CPM',
                        maxBid: {'$gte': !$.empty(GeolocationDB)?GeolocationDB.minBidCPM:$.Conf['minBidCPM']},
                        geoTargeting: {$in:[null, !$.empty(GeolocationDB)?GeolocationDB.countryCode:null]},
                        categoryTargeting: {$in:[null, AdspaceDB.categoryId]},
                        "$where": "this.maxBid < this.budget",
                        osTargeting:{$in:[null,os]},
                        browserTargeting: {$in:[null,parsed.userAgent.family]},
                        imageSizes: {$in:adsize},
                        webDesktopAdType: {$in:webDesktopAdType},
                        "$and":[
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
                            ]}
                        ],
                        campaignId: {
                            "$not": {
                                "$in":$._.chain(req.cookies.wfc).filter(function(num){ return num.banned==1; }).pluck("campaignId").map(function(num){return parseInt(num); }).value()
                            }
                        }
                    },{
                        campaignId          :1,
                        userId              :1,
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
                        imageWidth          :1,
                        imageHeight         :1,
                        CPCorCPM            :1,
                        isFixedBid          :1,
                        imageSizes          :1,
                        iframe              :1,
                        campaignType        :1,
                        tagTypeId           :1
                    }).exec(function(err,result){
                        if(result.length>0){
                            $.webBanerCompetition(
                                AdspaceDB,
                                result,
                                options,
                                {
                                    minBidCPM:((!$.empty(GeolocationDB))?GeolocationDB.minBidCPM:$.Conf['minBidCPM']),
                                    MinIntegralPayCPM:$.Conf['MinIntegralPayCPM']
                                },
                                function(data){
                                    var tmpParams = [];

                                    $.webFcCampaign(req,data,function(fc){
                                        res.cookie('wfc', fc, { maxAge: 2592000000, httpOnly: false });
                                    });

                                    var recursion = function(data, current, next){
                                        if(data.length != 0){
                                            var tmp = data[0];
                                            data = data.splice(1);
                                            current(tmp, data, next);
                                        } else {
                                            next();
                                        }
                                    };
                                    var rep = function(value, data, next) {
                                        var token  = $.generateUniqueId()+$.md5(req.sessionID);

                                        if(!value.potential) {
                                            model.Reports.findOneAndUpdate({
                                                campaignId: value.key,
                                                adspaceId: AdspaceDB['adspaceId'],
                                                advertiserId: value.user,
                                                publisherId: AdspaceDB['userId'],
                                                globalType: "web",
                                                type: value.type,
                                                showPlace: 1,
                                                CPCorCPM: 'CPM',
                                                date: (date.getFullYear() + "-" + (((date.getMonth() + 1) <= 9) ? "0" + (date.getMonth() + 1) : (date.getMonth() + 1)) + "-" + ((date.getDate() <= 9) ? "0" + date.getDate() : date.getDate())),
                                                geo: !$.empty(GeolocationDB) ? GeolocationDB.countryCode : null,
                                                deviceType: 'PC',
                                                deviceModel: parsed.device.family,
                                                os: parsed.os.family,
                                                osMajor: null,
                                                osMinor: null,
                                                browser: parsed.userAgent.family,
                                                editing: 1
                                            }, {
                                                $inc: {
                                                    conversion: +0,
                                                    price: +value.price,
                                                    impression: +1,
                                                    clickImpression: +0
                                                }, $set: {
                                                    commission: AdspaceDB['commission'],
                                                    advertiserShaping: value.shaping,
                                                    publisherShaping: AdspaceDB['shaping'],
                                                    dateISO: Date.now()
                                                }
                                            }, {
                                                upsert: true,
                                                multi: false,
                                                new: true
                                            }, function (err, docs) {
                                                $.createTargetUrl({
                                                    targetUrl: value.url,
                                                    token: docs._id,
                                                    remoteAddress: req.connection.remoteAddress,
                                                    websiteId: req.params.websiteId,
                                                    deviceType: 'PC',
                                                    os: parsed.os.family,
                                                    geo: !$.empty(GeolocationDB) ? GeolocationDB.countryCode : null,
                                                    model: parsed.device.family
                                                }, function (targetUrl) {
                                                    tmpParams.push({
                                                        createdUrl: $.Conf['node_host']+"/webtraffic/cpccount?statid="+docs._id+"&token="+token+"&cmgid="+value._id+"&cmsid="+value.key+"&cpcad="+value.isClickAd, //(result[key]['CPCorCPM']=='CPM' && result[key]['isClickAd']!=1)?targetUrl:
                                                        url: targetUrl,
                                                        domain: $.Conf['damainName'],
                                                        node_host: $.Conf['node_host'],
                                                        imgDir: $.Conf['damainName'] + $.Conf['imageUrl'] + '/' + value.image,
                                                        campaignId: value.key,
                                                        adspaceId: AdspaceDB['adspaceId'],
                                                        type: value.type,
                                                        globalTypeId: value.globalTypeId,
                                                        appearance: value.appearance,
                                                        imageWidth: value.imageWidth,
                                                        imageHeight: value.imageHeight,
                                                        imageOrLanding: value.imageOrLanding,
                                                        divId: value.divId,
                                                        iframe: value.iframe,
                                                        campaignType: value.campaignType,
                                                        tagTypeId: value.tagTypeId
                                                    });
                                                    var dataSave = model.TmpClick({
                                                        expireTime:Date.now()+1000*60*10,
                                                        price:(value.CPCorCPM==='CPC' || value.isClickAd==1?value.price:0),
                                                        token:token
                                                    });
                                                    dataSave.save(function(err,session){});
                                                    recursion(data, rep, next);
                                                });
                                            });
                                            model.Campaigns.update({_id: value._id}, {$inc: {budget: -value.price}}, function (err, result) {
                                            });
                                        } else {
                                            console.log("Potential");

                                            model.PotentialReports.update({
                                                adspaceId       :AdspaceDB.adspaceId,
                                                publisherId     :AdspaceDB.userId,
                                                availableTypes  :AdspaceDB.typeId,
                                                showPlace       :1,
                                                date            :(date.getFullYear()+"-"+(((date.getMonth()+1)<=9)?"0"+(date.getMonth()+1):(date.getMonth()+1))+"-"+((date.getDate()<=9)?"0"+date.getDate():date.getDate())),
                                                geo             :!$.empty(GeolocationDB)?GeolocationDB.countryCode:null,
                                                deviceType      :'PC',
                                                os              :parsed.os.family,
                                                osMajor         :null,
                                                osMinor         :null,
                                                browser         :parsed.userAgent.family,
                                                editing         :1,
                                                imageSize       :value.imageSize
                                            },{$inc:{
                                                impression      :+1
                                            },$set:{
                                                commission:AdspaceDB['commission'],
                                                publisherShaping:AdspaceDB['shaping'],
                                                dateISO:Date.now()
                                            }},{upsert : true, multi : false, new: true},function(err,doc){
                                                var d = 0;
                                            });

                                            recursion(data, rep, next);
                                        }
                                    }

                                    recursion(data, rep, function(){
                                        res.end(" var params="+JSON.stringify(tmpParams)+"; require('"+$.Conf['damainName']+"/system/js/webBanerAdType/webBanner.js',function(){ });");
                                        console.timeEnd('test');
                                    });
                            });
                        } else {
                            console.log("Potential");

                            $.webFcCampaign(req, [],function(fc){
                                res.cookie('wfc', fc, { maxAge: 2592000000, httpOnly: true });
                            });

                            res.end();
                            for(var i = 0; i < adsize.length; i++) {
                                model.PotentialReports.update({
                                    adspaceId: AdspaceDB.adspaceId,
                                    publisherId: AdspaceDB.userId,
                                    availableTypes: AdspaceDB.typeId,
                                    showPlace: 1,
                                    date: (date.getFullYear() + "-" + (((date.getMonth() + 1) <= 9) ? "0" + (date.getMonth() + 1) : (date.getMonth() + 1)) + "-" + ((date.getDate() <= 9) ? "0" + date.getDate() : date.getDate())),
                                    geo: !$.empty(GeolocationDB) ? GeolocationDB.countryCode : null,
                                    deviceType: 'PC',
                                    os: parsed.os.family,
                                    osMajor: null,
                                    osMinor: null,
                                    browser: parsed.userAgent.family,
                                    editing: 1,
                                    imageSize: adsize[i]
                                }, {
                                    $inc: {
                                        impression: +1
                                    }, $set: {
                                        commission: AdspaceDB['commission'],
                                        publisherShaping: AdspaceDB['shaping'],
                                        dateISO: Date.now()
                                    }
                                }, {upsert: true, multi: false, new: true}, function (err, doc) {
                                    var d = 0;
                                });
                            }
                        }
                    });
                });
            }
        });
    });

    app.get('/webtraffic/cpccount', function(req, res){
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
			
			res.render('web/redirect',{
				redirection:req.query.url
			});
                    });
                });
            }else{
                res.render('web/redirect',{
			redirection:req.query.url
		});
            }
        });
    });
}