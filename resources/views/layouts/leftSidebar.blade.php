<div class="page-sidebar-wrapper">
    <div class="page-sidebar navbar-collapse collapse">
        <ul class="page-sidebar-menu " data-keep-expanded="false" data-auto-scroll="true" data-slide-speed="200">
            <li class="sidebar-toggler-wrapper">
                <div class="sidebar-toggler"></div>
            </li>
            <li class="tooltips" data-container="body" data-placement="right" data-html="true" data-original-title="{{ trans('adminUsers.page_link_hover') }}">
                <a href="{{ route('users.index') }}">
                    <i class="fa fa-users"></i>
					<span class="title">{{ trans('adminUsers.page_link_title') }}</span>
                </a>
            </li>
            <li class="tooltips" data-container="body" data-placement="right" data-html="true" data-original-title="{{ trans('adminNames.names_page_link_hover') }}">
                <a href="{{ route('names.index') }}">
                    <i class="fa fa-font"></i>
					<span class="title">{{ trans('adminNames.names_page_link_title') }}</span>
                </a>
            </li>
            <li class="tooltips" data-container="body" data-placement="right" data-html="true" data-original-title="{{ trans('adminCategories.categories_page_link_hover') }}">
                <a href="{{ route('categories.index') }}">
                    <i class="fa fa-sitemap"></i>
					<span class="title">{{ trans('adminCategories.categories_page_link_title') }}</span>
                </a>
            </li>
            <li class="tooltips" data-container="body" data-placement="right" data-html="true" data-original-title="{{ trans('adminTemplates.templates_page_link_hover') }}">
                <a href="{{ route('templates.index') }}">
                    <i class="fa fa-codepen"></i>
					<span class="title">{{ trans('adminTemplates.templates_page_link_title') }}</span>
                </a>
            </li>
            <li class="tooltips" data-container="body" data-placement="right" data-html="true" data-original-title="{{ trans('adminProducts.products_page_link_hover') }}">
                <a href="{{ route('products.index') }}">
                    <i class="fa fa-tags"></i>
					<span class="title">{{ trans('adminProducts.products_page_link_title') }}</span>
                </a>
            </li>
        </ul>
    </div>
</div>