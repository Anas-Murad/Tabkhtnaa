<div class="col-md-12">
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">الملف الشخصي</h5>
        </div>
        <div class="card-body border-top">
            <div class="row gx-1 gy-1">
                <div class="col-md-6">
                    <a href="{{route('complaints.index' , ['user_id'=>$user->id])}}">
                        <button type="submit" class="btn btn-info d-block w-100 border-0">
                            الشكاوي
                        </button>
                    </a>
                </div>
                <div class="col-md-6">
                    <a href="{{route('sanctions.index' , ['user_id'=>$user->id])}}">
                        <button type="submit"  class="btn btn-warning d-block w-100 border-0">
                            العقوبات
                        </button>
                    </a>
                </div>
                <div class="col-md-2">
                    <a href="{{route('admin.offer.index',['type'=>'offers' , 'user_id'=>$user->id])}}">
                        <button type="submit" class="btn btn-dark d-block w-100 border-0">
                            العروض
                        </button>
                    </a>
                </div>
                <div class="col-md-2">
                    <a href="{{route('admin.offer.index',['type'=>'discount' , 'user_id'=>$user->id])}}">
                        <button type="submit" class="btn btn-teal d-block w-100 border-0">
                            الخصومات
                        </button>
                    </a>
                </div>
                <div class="col-md-2">
                    <a href="{{route('admin.meals.index' , ['status' => 'confirmed' , 'user_id'=>$user->id])}}">
                        <button type="button" class="btn btn-indigo d-block w-100 border-0">
                            تفاصيل الوجبات
                        </button>
                    </a>
                </div>
                <div class="col-md-2">
                    <a href="{{route('admin.evaluation.index' , ['user_id'=>$user->id])}}">
                        <button type="submit"  class="btn btn-primary d-block w-100 border-0">
                            التقيم له
                        </button>
                    </a>
                </div>
                <div class="col-md-2">
                    <button type="submit"   class="btn btn-yellow d-block w-100 border-0">
                       التميز له
                    </button>
                </div>
                <div class="col-md-2">
                    <a href="{{route('admin.orders.index' , ['user_id'=>$user->id , 'user_type'=>$user->type])}}">
                        <button type="button" class="btn btn-indigo d-block w-100 border-0">
                            حركات الطلبات الخاصة به
                        </button>
                    </a>
                </div>
                <div class="col-md-12">
                    <button type="submit" class="btn btn-danger d-block w-100 border-0">
                        الامور المالية
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
