<div class="sidebar sidebar-dark sidebar-main sidebar-expand-lg">
    <!-- Sidebar content -->
    <div class="sidebar-content">
        <!-- Sidebar header -->
        <div class="sidebar-section">
            <div class="sidebar-section-body d-flex justify-content-center">
                <h5 class="sidebar-resize-hide flex-grow-1 my-auto">Navigation</h5>
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
                <!-- Main -->
                <!-- Forms -->
                <li class="nav-item-header">
                    <div class="text-uppercase fs-sm lh-sm opacity-50 sidebar-resize-hide">Translation</div>
                    <i class="ph-dots-three sidebar-resize-show"></i>
                </li>
                <li class="nav-item nav-item-submenu">
                    <a href="#" class="nav-link">
                        <i class="ph-note-pencil"></i>
                        <span>Translation</span>
                    </a>
                    <ul class="nav-group-sub collapse">
                        <li class="nav-item"><a href="{{route('translations.index')}}" class="nav-link">List</a></li>
                    </ul>
                </li>
                <!-- /forms -->
            </ul>
            <ul class="nav nav-sidebar" data-nav-type="accordion">
                <!-- Main -->
                <!-- Forms -->
                <li class="nav-item-header">
                    <div class="text-uppercase fs-sm lh-sm opacity-50 sidebar-resize-hide">Complaints</div>
                    <i class="ph-dots-three sidebar-resize-show"></i>
                </li>
                <li class="nav-item nav-item-submenu">
                    <a href="#" class="nav-link">
                        <i class="ph-note-pencil"></i>
                        <span>Complaints</span>
                    </a>
                    <ul class="nav-group-sub collapse">
                        <li class="nav-item"><a href="{{route('complaints.index')}}" class="nav-link">List</a></li>
                    </ul>
                </li>
                <!-- /forms -->
            </ul>
            <ul class="nav nav-sidebar" data-nav-type="accordion">
                <!-- Main -->
                <!-- Forms -->
                <li class="nav-item-header">
                    <div class="text-uppercase fs-sm lh-sm opacity-50 sidebar-resize-hide">Sanctions</div>
                    <i class="ph-dots-three sidebar-resize-show"></i>
                </li>
                <li class="nav-item nav-item-submenu">
                    <a href="#" class="nav-link">
                        <i class="ph-note-pencil"></i>
                        <span>Sanctions</span>
                    </a>
                    <ul class="nav-group-sub collapse">
                        <li class="nav-item"><a href="{{route('sanctions.index')}}" class="nav-link">List</a></li>
                    </ul>
                </li>
                <!-- /forms -->
            </ul>
            <ul class="nav nav-sidebar" data-nav-type="accordion">
                <!-- Main -->
                <!-- Forms -->
                <li class="nav-item-header">
                    <div class="text-uppercase fs-sm lh-sm opacity-50 sidebar-resize-hide">Meals</div>
                    <i class="ph-dots-three sidebar-resize-show"></i>
                </li>
                <li class="nav-item nav-item-submenu">
                    <a href="#" class="nav-link">
                        <i class="ph-note-pencil"></i>
                        <span>Meals</span>
                    </a>
                    <ul class="nav-group-sub collapse">
                        <li class="nav-item"><a href="{{route('admin.meals.index' , ['status' => 'new'])}}" class="nav-link">new
                        <span class="badge bg-teal align-self-center rounded-pill ms-auto">4.0</span>  </a></li>
                        <li class="nav-item"><a href="{{route('admin.meals.index' , ['status' => 'confirmed'])}}" class="nav-link">confirmed
                            <span class="badge bg-light  text-black align-self-center rounded-pill ms-auto">4.0</span>  </a></li>
                        <li class="nav-item"><a href="{{route('admin.meals.index', ['status' => 'disabled'])}}" class="nav-link">disabled
                                <span class="badge bg-warning align-self-center rounded-pill ms-auto">4.0</span></a></li>
                    </ul>
                </li>
                <!-- /forms -->
            </ul>
            <ul class="nav nav-sidebar" data-nav-type="accordion">
                <!-- Main -->
                <!-- Forms -->
                <li class="nav-item-header">
                    <div class="text-uppercase fs-sm lh-sm opacity-50 sidebar-resize-hide">Offers</div>
                    <i class="ph-dots-three sidebar-resize-show"></i>
                </li>
                <li class="nav-item nav-item-submenu">
                    <a href="#" class="nav-link">
                        <i class="ph-note-pencil"></i>
                        <span>Offers</span>
                    </a>
                    <ul class="nav-group-sub collapse">
                        <li class="nav-item"><a href="{{route('admin.offer.index',['type'=>'discount'])}}" class="nav-link">Discount</a></li>
                        <li class="nav-item"><a href="{{route('admin.offer.index',['type'=>'offers'])}}" class="nav-link">Offers</a></li>
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
                            <a href="#" class="nav-link">  حسابات معطله Blocked</a>
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
        <li class="nav-item"><a href="{{ route('admin.orders.index',['status'=>'pending']) }}" class="nav-link"> قيد الانتظار
            <span class="badge bg-teal align-self-center rounded-pill ms-auto">4.0</span>  </a></li>
        <li class="nav-item"><a href="{{ route('admin.orders.index',['status'=>'confirmed']) }}" class="nav-link">تم قبولها
            <span class="badge bg-light  text-black align-self-center rounded-pill ms-auto">4.0</span>  </a></li>
        <li class="nav-item"><a href="{{ route('admin.orders.index',['status'=>'prepare']) }}" class="nav-link">قيد التحضير
            <span class="badge bg-black align-self-center rounded-pill ms-auto">4.0</span>  </a></li>
        <li class="nav-item"><a href="{{ route('admin.orders.index',['status'=>'prepared']) }}" class="nav-link">تم التحضير
            <span class="badge bg-primary align-self-center rounded-pill ms-auto">4.0</span>  </a></li>
        <li class="nav-item"><a href="{{ route('admin.orders.index',['status'=>'on_way']) }}" class="nav-link">في الطريق
            <span class="badge bg-info align-self-center rounded-pill ms-auto">4.0</span>  </a></li>
        <li class="nav-item"><a href="{{ route('admin.orders.index',['status'=>'delivered']) }}" class="nav-link">تم التوصيل
            <span class="badge bg-success align-self-center rounded-pill ms-auto">4.0</span>  </a></li>
        <li class="nav-item"><a href="{{ route('admin.orders.index',['status'=>'not_delivered']) }}" class="nav-link">لم يتم التوصيل
            <span class="badge bg-warning align-self-center rounded-pill ms-auto">4.0</span>  </a></li>
        <li class="nav-item"><a href="{{ route('admin.orders.index',['status'=>'rejected']) }}" class="nav-link">تم رفضه
            <span class="badge bg-danger align-self-center rounded-pill ms-auto">4.0</span>  </a></li>

        <li class="nav-item"><a href="{{ route('admin.orders.index',['status'=>'cancel']) }}" class="nav-link">ملغي
            <span class="badge bg-secondary align-self-center rounded-pill ms-auto">4.0</span>  </a></li>
        <li class="nav-item"><a href="{{ route('admin.orders.index',['status'=>'not_ordered']) }}" class="nav-link"> مازال غير مطلوب
            <span class="badge bg-dark align-self-center rounded-pill ms-auto">4.0</span>  </a></li>


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

