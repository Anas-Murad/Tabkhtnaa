<div class="sidebar sidebar-dark sidebar-main sidebar-expand-lg">
    <!-- Sidebar content -->
    <div class="sidebar-content">
        <!-- Sidebar header -->
        <div class="sidebar-section">
            <div class="sidebar-section-body d-flex justify-content-center">
                <h5 class="sidebar-resize-hide flex-grow-1 my-auto">القائمة</h5>
                <div>
                    <button type="button" class="btn btn-flat-white btn-icon btn-sm rounded-pill border-transparent sidebar-control sidebar-main-resize d-none d-lg-inline-flex">
                        <i class="ph-arrows-left-right"></i>
                    </button>
                    <button type="button" class="btn btn-flat-white btn-icon btn-sm rounded-pill border-transparent sidebar-mobile-main-toggle d-lg-none">
                        <i class="ph-x"></i>
                    </button>
                </div>
            </div>
        </div>
        <!-- /sidebar header -->
        <!-- Main navigation -->
        <div class="sidebar-section">
            <ul class="nav nav-sidebar" data-nav-type="accordion">
                <li class="nav-item-header">
                    <div class="text-uppercase fs-sm lh-sm opacity-50 sidebar-resize-hide">الإعدادات</div>
                    <i class="ph-dots-three sidebar-resize-show"></i>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.settings.edit') }}" class="nav-link">
                        <i class="ph-gear"></i>
                        <span>إعدادات النظام</span>
                    </a>
                </li>
                <li class="nav-item nav-item-submenu">
                    <a href="#" class="nav-link">
                        <i class="ph-star"></i>
                        <span>نظام الولاء</span>
                    </a>
                    <ul class="nav-group-sub collapse">
                        <li class="nav-item"><a href="{{ route('admin.loyalty.dashboard') }}" class="nav-link">لوحة التحكم</a></li>
                        <li class="nav-item"><a href="{{ route('admin.loyalty.settings.edit') }}" class="nav-link">الإعدادات</a></li>
                        <li class="nav-item"><a href="{{ route('admin.loyalty.tiers.index') }}" class="nav-link">المستويات</a></li>
                        <li class="nav-item"><a href="{{ route('admin.loyalty.transactions.index') }}" class="nav-link">حركات النقاط</a></li>
                        <li class="nav-item"><a href="{{ route('admin.loyalty.campaigns.index') }}" class="nav-link">حملات المضاعفة</a></li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.audit_trails.index') }}" class="nav-link">
                        <i class="ph-clipboard-text"></i>
                        <span>سجل التدقيق</span>
                    </a>
                </li>
            </ul>
            <ul class="nav nav-sidebar" data-nav-type="accordion">
                <!-- Main -->
                <!-- Forms -->
                <li class="nav-item-header">
                    <div class="text-uppercase fs-sm lh-sm opacity-50 sidebar-resize-hide">الترجمة</div>
                    <i class="ph-dots-three sidebar-resize-show"></i>
                </li>
                <li class="nav-item nav-item-submenu">
                    <a href="#" class="nav-link">
                        <i class="ph-note-pencil"></i>
                        <span>الترجمة</span>
                    </a>
                    <ul class="nav-group-sub collapse">
                        <li class="nav-item"><a href="{{route('translations.index')}}" class="nav-link">القائمة</a></li>
                    </ul>
                </li>
                <!-- /forms -->
            </ul>
            <ul class="nav nav-sidebar" data-nav-type="accordion">
                <!-- Main -->
                <!-- Forms -->
                <li class="nav-item-header">
                    <div class="text-uppercase fs-sm lh-sm opacity-50 sidebar-resize-hide">الشكاوى</div>
                    <i class="ph-dots-three sidebar-resize-show"></i>
                </li>
                <li class="nav-item nav-item-submenu">
                    <a href="#" class="nav-link">
                        <i class="ph-note-pencil"></i>
                        <span>الشكاوى</span>
                    </a>
                    <ul class="nav-group-sub collapse">
                        <li class="nav-item"><a href="{{route('complaints.index')}}" class="nav-link">القائمة</a></li>
                    </ul>
                </li>
                <!-- /forms -->
            </ul>
            <ul class="nav nav-sidebar" data-nav-type="accordion">
                <!-- Main -->
                <!-- Forms -->
                <li class="nav-item-header">
                    <div class="text-uppercase fs-sm lh-sm opacity-50 sidebar-resize-hide">المخالفات</div>
                    <i class="ph-dots-three sidebar-resize-show"></i>
                </li>
                <li class="nav-item nav-item-submenu">
                    <a href="#" class="nav-link">
                        <i class="ph-note-pencil"></i>
                        <span>المخالفات</span>
                    </a>
                    <ul class="nav-group-sub collapse">
                        <li class="nav-item"><a href="{{route('sanctions.index')}}" class="nav-link">القائمة</a></li>
                    </ul>
                </li>
                <!-- /forms -->
            </ul>
            <ul class="nav nav-sidebar" data-nav-type="accordion">
                <!-- Main -->
                <!-- Forms -->
                <li class="nav-item-header">
                    <div class="text-uppercase fs-sm lh-sm opacity-50 sidebar-resize-hide">الوجبات</div>
                    <i class="ph-dots-three sidebar-resize-show"></i>
                </li>
                <li class="nav-item nav-item-submenu">
                    <a href="#" class="nav-link">
                        <i class="ph-cooking-pot"></i>
                        <span>الوجبات</span>
                    </a>
                    <ul class="nav-group-sub collapse">
                        <li class="nav-item"><a href="{{route('admin.meals.index' , ['status' => 'new'])}}" class="nav-link">جديدة</a></li>
                        <li class="nav-item"><a href="{{route('admin.meals.index' , ['status' => 'confirmed'])}}" class="nav-link">مؤكدة</a></li>
                        <li class="nav-item"><a href="{{route('admin.meals.index', ['status' => 'disabled'])}}" class="nav-link">معطلة</a></li>
                    </ul>
                </li>
                <!-- /forms -->
            </ul>
            <ul class="nav nav-sidebar" data-nav-type="accordion">
                <!-- Main -->
                <!-- Forms -->
                <li class="nav-item-header">
                    <div class="text-uppercase fs-sm lh-sm opacity-50 sidebar-resize-hide">العروض</div>
                    <i class="ph-dots-three sidebar-resize-show"></i>
                </li>
                <li class="nav-item nav-item-submenu">
                    <a href="#" class="nav-link">
                        <i class="ph-percent"></i>
                        <span>العروض</span>
                    </a>
                    <ul class="nav-group-sub collapse">
                        <li class="nav-item"><a href="{{route('admin.offer.index',['type'=>'discount'])}}" class="nav-link">خصومات</a></li>
                        <li class="nav-item"><a href="{{route('admin.offer.index',['type'=>'offers'])}}" class="nav-link">عروض</a></li>
                    </ul>
                </li>
                <!-- /forms -->
            </ul>
            <ul class="nav nav-sidebar" data-nav-type="accordion">
                <!-- Main -->
                <!-- Forms -->
                <li class="nav-item-header">
                    <div class="text-uppercase fs-sm lh-sm opacity-50 sidebar-resize-hide"></div>
                    <i class="ph-dots-three sidebar-resize-show"></i>
                </li>


                <li class="nav-item nav-item-submenu">
                    <a href="#" class="nav-link"><i class="ph-users-three"></i> <span>ادارة المستخدمين</span></a>
                    <ul class="nav-group-sub collapse">

                        <li class="nav-item"><a href="{{ route('users.index') }}" class="nav-link">الكل</a></li>

                <li class="nav-item nav-item-submenu">
                    <a href="#" class="nav-link">قيد الانتظار / جديد</a>
                    <ul class="nav-group-sub collapse">
                        <li class="nav-item"><a href="{{ route('usersByStatusType' ,['pending' , 'chef']) }}" class="nav-link">الطهاه</a></li>
                        <li class="nav-item"><a href="{{ route('usersByStatusType' ,['pending' , 'delivery']) }}" class="nav-link">التوصيل</a></li>
                    </ul>
                </li>

                        <li class="nav-item nav-item-submenu">
                            <a href="#" class="nav-link">تم قبولهم / نشط</a>
                            <ul class="nav-group-sub collapse">
                                <li class="nav-item"><a href="{{ route('usersByStatusType' ,['active' , 'chef']) }}"  class="nav-link">الطهاه</a></li>
                                <li class="nav-item"><a href="{{ route('usersByStatusType' ,['active' , 'delivery']) }}"  class="nav-link">التوصيل</a></li>
                            </ul>
                        </li>

                        <li class="nav-item nav-item-submenu">
                            <a href="#" class="nav-link">طلبات انضمام مرفوضه</a>
                            <ul class="nav-group-sub collapse">
                                <li class="nav-item"><a href="{{ route('usersByStatusType' ,['rejected' , 'chef']) }}"  class="nav-link">الطهاه</a></li>
                                <li class="nav-item"><a href="{{ route('usersByStatusType' ,['rejected' , 'delivery']) }}"  class="nav-link">التوصيل</a></li>
                            </ul>
                        </li>

                        <li class="nav-item nav-item-submenu">
                            <a href="#" class="nav-link">  حسابات معطلة</a>
                            <ul class="nav-group-sub collapse">
                                <li class="nav-item"><a href="{{ route('usersByStatusType' ,['blocked' , 'chef']) }}"  class="nav-link">الطهاه</a></li>
                                <li class="nav-item"><a href="{{ route('usersByStatusType' ,['blocked' , 'delivery']) }}"  class="nav-link">التوصيل</a></li>
                                <li class="nav-item"><a href="{{ route('usersByStatusType' ,['blocked' , 'client']) }}" class="nav-link">المستخدمين</a></li>
                            </ul>
                        </li>

                        <li class="nav-item"><a href="{{ route('usersByStatusType' , [ 'all','client']) }}" class="nav-link">مستخدمي التطبيق</a></li>
{{--
                        <li class="nav-item nav-item-submenu">
                            <a href="#" class="nav-link">Second level with child</a>
                            <ul class="nav-group-sub collapse">
                                <li class="nav-item"><a href="#" class="nav-link">Third level</a></li>
                                <li class="nav-item nav-item-submenu">
                                    <a href="#" class="nav-link">Third level with child</a>
                                    <ul class="nav-group-sub collapse">
                                        <li class="nav-item"><a href="#" class="nav-link">Fourth level</a></li>
                                        <li class="nav-item"><a href="#" class="nav-link">Fourth level</a></li>
                                    </ul>
                                </li>
                                <li class="nav-item"><a href="#" class="nav-link">Third level</a></li>
                            </ul>
                        </li>

                        <li class="nav-item"><a href="#" class="nav-link">Second level</a></li>--}}

                    </ul>
                </li>


                <li class="nav-item nav-item-submenu">
                    <a href="#" class="nav-link"><i class="ph-users-three"></i> <span>قسم الطلبات</span></a>
                    <ul class="nav-group-sub collapse">



        <li class="nav-item"><a href="{{ route('admin.orders.index') }}" class="nav-link">كل الطلبات</a></li>
        <li class="nav-item"><a href="{{ route('admin.orders.index',['status'=>'pending']) }}" class="nav-link">قيد الانتظار</a></li>
        <li class="nav-item"><a href="{{ route('admin.orders.index',['status'=>'confirmed']) }}" class="nav-link">تم قبولها</a></li>
        <li class="nav-item"><a href="{{ route('admin.orders.index',['status'=>'prepare']) }}" class="nav-link">قيد التحضير</a></li>
        <li class="nav-item"><a href="{{ route('admin.orders.index',['status'=>'prepared']) }}" class="nav-link">تم التحضير</a></li>
        <li class="nav-item"><a href="{{ route('admin.orders.index',['status'=>'on_way']) }}" class="nav-link">في الطريق</a></li>
        <li class="nav-item"><a href="{{ route('admin.orders.index',['status'=>'delivered']) }}" class="nav-link">تم التوصيل</a></li>
        <li class="nav-item"><a href="{{ route('admin.orders.index',['status'=>'not_delivered']) }}" class="nav-link">لم يتم التوصيل</a></li>
        <li class="nav-item"><a href="{{ route('admin.orders.index',['status'=>'rejected']) }}" class="nav-link">تم رفضه</a></li>
        <li class="nav-item"><a href="{{ route('admin.orders.index',['status'=>'cancel']) }}" class="nav-link">ملغي</a></li>
        <li class="nav-item"><a href="{{ route('admin.orders.index',['status'=>'not_ordered']) }}" class="nav-link">غير مطلوب</a></li>


                    </ul>
                </li>


                <li class="nav-item nav-item-submenu">
                    <a href="#" class="nav-link"><i class="ph-users-three"></i> <span>الحاله الماليه للطلبات</span></a>
                    <ul class="nav-group-sub collapse">

                        <li class="nav-item"><a href="{{ route('admin.orders.index') }}" class="nav-link">كل الطلبات</a></li>
                        <li class="nav-item"><a href="{{ route('admin.orders.index',['status'=>'all' , 'transactionStatus'=>'pending']) }}" class="nav-link"> قيد الانتظار</a></li>
                        <li class="nav-item"><a href="{{ route('admin.orders.index',['status'=>'all' , 'transactionStatus'=>'success']) }}" class="nav-link">تم  السداد</a></li>
                        <li class="nav-item"><a href="{{ route('admin.orders.index',['status'=>'all' , 'transactionStatus'=>'cancel']) }}" class="nav-link">ملغي</a></li>

                    </ul>
                </li>

             {{--   'payment_method' => ['required'  , 'in:wallet,cash,cards'],
                delivery_type	enum('delivery', 'pick_up', 'chef_delivery')
                transaction_status	enum('pending', 'success', 'cancel')
--}}

{{--


                <li class="nav-item nav-item-submenu">
                    <a href="#" class="nav-link">
                        <i class="ph-note-pencil"></i>
                        <span>Sanctions</span>
                    </a>
                    <ul class="nav-group-sub collapse">
                        <li class="nav-item"><a href="{{route('sanctions.index')}}" class="nav-link">List</a></li>
                    </ul>
                </li>


--}}


                <!-- /forms -->
            </ul>
        </div>
        <!-- /main navigation -->
    </div>
    <!-- /sidebar content -->
</div>

