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
                <li class="nav-item nav-item-submenu">
                    <ul class="nav-group-sub collapse">
                        <li class="nav-item"><a href="{{route('complaints.index')}}" class="nav-link">List</a></li>
                    </ul>
                </li>
                <!-- /forms -->
            </ul>
            <ul class="nav nav-sidebar" data-nav-type="accordion">
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
                    </ul>
                </li>


                <li class="nav-item nav-item-submenu">
                    <a href="#" class="nav-link"><i class="ph-users-three"></i> <span>قسم الطلبات</span></a>
                    <ul class="nav-group-sub collapse">




                        <li class="nav-item"><a href="{{ route('admin.orders.index') }}" class="nav-link"> كل الطلبات
                                <span class="badge bg-info align-self-center rounded-pill ms-auto">{{$SB_TOTAL_ORDERS}}</span>  </a></li>


        <li class="nav-item"><a href="{{ route('admin.orders.index',['status'=>'pending']) }}" class="nav-link"> قيد الانتظار
            <span class="badge bg-teal align-self-center rounded-pill ms-auto">{{$SB_PENDING_ORDERS}}</span>  </a></li>
        <li class="nav-item"><a href="{{ route('admin.orders.index',['status'=>'confirmed']) }}" class="nav-link">تم قبولها
            <span class="badge bg-light  text-black align-self-center rounded-pill ms-auto">{{$SB_CONFIRMED_ORDERS}}</span>  </a></li>
        <li class="nav-item"><a href="{{ route('admin.orders.index',['status'=>'prepare']) }}" class="nav-link">قيد التحضير
            <span class="badge bg-black align-self-center rounded-pill ms-auto">{{$SB_PREPARE_ORDERS}}</span>  </a></li>
        <li class="nav-item"><a href="{{ route('admin.orders.index',['status'=>'prepared']) }}" class="nav-link">تم التحضير
            <span class="badge bg-primary align-self-center rounded-pill ms-auto">{{$SB_PREPARED_ORDERS}}</span>  </a></li>
        <li class="nav-item"><a href="{{ route('admin.orders.index',['status'=>'on_way']) }}" class="nav-link">في الطريق
            <span class="badge bg-info align-self-center rounded-pill ms-auto">{{$SB_ON_WAY_ORDERS}}</span>  </a></li>
        <li class="nav-item"><a href="{{ route('admin.orders.index',['status'=>'delivered']) }}" class="nav-link">تم التوصيل
            <span class="badge bg-success align-self-center rounded-pill ms-auto">{{$SB_DELIVERED_ORDERS}}</span>  </a></li>
        <li class="nav-item"><a href="{{ route('admin.orders.index',['status'=>'not_delivered']) }}" class="nav-link">لم يتم التوصيل
            <span class="badge bg-warning align-self-center rounded-pill ms-auto">{{$SB_NOT_DELIVERED_ORDERS}}</span>  </a></li>
        <li class="nav-item"><a href="{{ route('admin.orders.index',['status'=>'rejected']) }}" class="nav-link">تم رفضه
            <span class="badge bg-danger align-self-center rounded-pill ms-auto">{{$SB_REJECTED_ORDERS}}</span>  </a></li>

        <li class="nav-item"><a href="{{ route('admin.orders.index',['status'=>'cancel']) }}" class="nav-link">ملغي
            <span class="badge bg-secondary align-self-center rounded-pill ms-auto">{{$SB_CANCEL_ORDERS}}</span>  </a></li>
        <li class="nav-item"><a href="{{ route('admin.orders.index',['status'=>'not_ordered']) }}" class="nav-link"> مازال غير مطلوب
            <span class="badge bg-dark align-self-center rounded-pill ms-auto">{{$SB_NOT_ORDERED_ORDERS}}</span>  </a></li>












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


            <li class="nav-item nav-item-submenu">
                <a href="#" class="nav-link"><i class="ph-users-three"></i> <span>عملاء التمييز</span></a>
                <ul class="nav-group-sub collapse">

                    <li class="nav-item"><a href="{{ route('admin.distinction.index') }}" class="nav-link">كل السجلات</a></li>
                    <li class="nav-item"><a href="{{ route('admin.distinction.index',[ 'status'=>'new']) }}" class="nav-link">  جديد</a></li>
                    <li class="nav-item"><a href="{{ route('admin.distinction.index',[ 'status'=>'active']) }}" class="nav-link">  نشط</a></li>
                    <li class="nav-item"><a href="{{ route('admin.distinction.index',[ 'status'=>'ended']) }}" class="nav-link">منتهي</a></li>
                    <li class="nav-item"><a href="{{ route('admin.distinction.index',[ 'status'=>'rejected']) }}" class="nav-link">مرفوض</a></li>

                </ul>
            </li>





    <li class="nav-item nav-item-submenu">
        <a href="#" class="nav-link"><i class="ph-users-three"></i> <span>الحركات الماليه </span></a>
        <ul class="nav-group-sub collapse">
            <li class="nav-item"><a href="{{ route('admin.transactions.index' , 'completed') }}" class="nav-link"> الحركات مكتمله الدفع</a></li>


            <li class="nav-item"><a href="{{ route('admin.transactions.index' , 'pending') }}" class="nav-link"> الحركات بانتظار المراجعه</a></li>
            <li class="nav-item"><a href="{{ route('admin.transactions.index' , 'success') }}" class="nav-link"> الحركات مرفوضة</a></li>
            <li class="nav-item"><a href="{{ route('admin.transactions.index' , 'failed') }}" class="nav-link"> الحركات مؤكده</a></li>


            <li class="nav-item"><a href="{{ route('admin.transactions.index' , 'uncompleted') }}" class="nav-link"> الحركات الغير مكتمله الدفع</a></li>
        </ul>
    </li>


            </ul>


            <ul class="nav nav-sidebar" data-nav-type="accordion">

                <li class="nav-item nav-item-submenu">
                    <a href="#" class="nav-link"><i class="ph-users-three"></i> <span> اعدادات التطبيق</span></a>
                    <ul class="nav-group-sub collapse">


                        <li class="nav-item"><a href="" class="nav-link">عموله التطبيق</a></li>
                        <li class="nav-item"><a href="" class="nav-link"> قيمه النقاط على كل طلب</a></li>
                        <li class="nav-item"><a href="" class="nav-link"> حد بلوغ التمييز</a></li>
                        <li class="nav-item"><a href="" class="nav-link">  حد  استبدال النقاط</a></li>
                        <li class="nav-item"><a href="" class="nav-link"> معادلة المسافه والتوصيل</a></li>


                        <li class="nav-item nav-item-submenu">
                            <a href="#" class="nav-link">الدول المتاحة</a>
                            <ul class="nav-group-sub collapse">
                                <li class="nav-item"><a href="" class="nav-link">قائمة الدول</a></li>
                                <li class="nav-item"><a href="" class="nav-link">اضف دولة</a></li>
                            </ul>
                        </li>


                    </ul>
                </li>



            </ul>

        </div>
        <!-- /main navigation -->
    </div>
    <!-- /sidebar content -->
</div>

