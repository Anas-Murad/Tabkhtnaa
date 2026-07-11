<?php

namespace App\DataTables;

use App\Models\Meal;
use App\Support\AdminLabels;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class MealsDataTable extends DataTable
{
    /**
     * Build DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     * @return \Yajra\DataTables\EloquentDataTable
     */
    protected $status;
    protected $user_id;

    public function __construct($status , $user_id)
    {
        $this->status = $status;
        $this->user_id = $user_id;
    }

    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->editColumn('image', '<img style=" width: 50px; height: 50px; " src="{{asset($image ?? "assets/images/demo/users/face1.jpg" )}}" />')

            ->editColumn('created_at', function ($meal) {
                return  $meal->created_at->toDateString();
            })
            ->editColumn('updated_at', function ($meal) {
                return  $meal->updated_at->toDateString();
            })
            ->editColumn('type', fn ($meal) => " <span class='badge bg-info text-white'>" . AdminLabels::mealType($meal->type) . '</span>')
            ->editColumn('is_active', fn ($meal) => $meal->is_active == 1
                ? "<span class='badge bg-success text-white'>" . __('messages.meal_active') . '</span>'
                : "<span class='badge bg-danger text-white'>" . __('messages.meal_inactive') . '</span>')
            ->editColumn('admin_status', fn ($meal) => AdminLabels::mealAdminStatus($meal->admin_status))
            ->editColumn('admin_note' , function ($meal){
                return $meal->admin_note ?? '-';
            })
            ->editColumn('preparation_time' , function ($meal){
                return $meal->preparation_time ?? '-';
            })
            ->editColumn('days' , function ($meal){
                return $meal->days ?? '-';
            })
            ->editColumn('action', function ($meal) {
                $editLink = 'EditMealFunction(' . htmlspecialchars(json_encode($meal), ENT_QUOTES, 'UTF-8') . ');';
                $showLink = route('admin.meals.show', $meal);
                $editLabel = __('messages.admin_edit');
                $showLabel = __('messages.admin_show');

                return <<<HTML
                <div class="d-inline-flex">
                        <div class="dropdown">
                            <a href="#" class="text-body" data-bs-toggle="dropdown">
                                <i class="ph-list"></i>
                            </a>
                        <div class="dropdown-menu dropdown-menu-end">
                            <a href="#" class="dropdown-item" onclick="{$editLink}"> <i class="ph-pencil me-2"></i> $editLabel </a>
                            <a href="$showLink" class="dropdown-item"> <i class="ph-eye me-2"></i> $showLabel </a>
                        </div>
                    </div>
                </div>
                HTML;
            })
            ->setRowId('id')
            ->rawColumns([
                'action',
                'image',
                'type',
                'is_active',
                'admin_status',
                'admin_note',
                'preparation_time',
                'days',
                'created_at',
                'updated_at',
            ])
            ;
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\Meal $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Meal $model): QueryBuilder
    {
        return $model->newQuery()
            ->with([
                'category' => function($q){
                     $q->select('id' , 'key');
                },
                'user' => function($q1){
                    $q1->select('id' , 'name');
                 }
            ])
            ->when($this->status , function ($q){
                $q->where('admin_status' , $this->status);
            })
            ->when($this->user_id , function ($q){
                $q->where('user_id' , $this->user_id);
            })
            ->when($this->request->filled('name_meal'), function ($q) {
                $q->where('name','like', '%' . $this->request()->input('name_meal') . '%');
            })
            ->when($this->request->filled('code_meal'), function ($q) {
                $q->where('code','like', '%' . $this->request()->input('code_meal') . '%');
            })
            ->when($this->request->filled('description_meal'), function ($q) {
                $q->where('description','like', '%' . $this->request()->input('description_meal') . '%');
            })
            ->when($this->request->filled('price_meal'), function ($q) {
                $q->where('price', '<' , $this->request()->input('price_meal'));
            })
            ->when($this->request->filled('from_date'), function ($q) {
                $q->where('created_at', '>=', $this->request->input('from_date'));
            })
            ->when($this->request->filled('to_date'), function ($q) {
                $q->where('created_at', '<=', $this->request->input('to_date'));
            })
            ->whereHas('user' , function ($q){
                $q->when($this->request->filled('search_key'), function ($q) {
                    $q->where(function ($q) {
                        $q->where('name', 'like', '%' . $this->request()->input('search_key') . '%');
                        $q->orWhere('email', 'like', '%' . $this->request()->input('search_key') . '%');
                        $q->orWhere('country_code', 'like', '%' . $this->request()->input('search_key') . '%');
                        $q->orWhere('mobile', 'like', '%' . $this->request()->input('search_key') . '%');
                        $q->orWhere('username', 'like', '%' . $this->request()->input('search_key') . '%');
                        $q->orWhere('account_comment', 'like', '%' . $this->request()->input('search_key') . '%');
                    });

                });
                $q->when($this->request->filled('country_id') || $this->request->filled('city_id'), function ($q) {
                    $r = [];
                    if ($this->request->filled('country_id')) $r['country_id'] = $this->request->country_id;
                    if ($this->request->filled('city_id')) $r['city_id'] = $this->request->country_id;
                    $q->whereRelation('userAddress', $r);
                });
            })
            ;
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
                    ->setTableId('data-table')
                    ->columns($this->getColumns())
                    ->minifiedAjax()
                    ->orderBy(13)
                    ->selectStyleSingle()
                    ->ajaxWithForm(url()->current(), '#filter_form')
                    ->buttons([
                        Button::make('excel'),
                        Button::make('csv'),
                        Button::make('print'),
                    ]);
    }

    /**
     * Get the dataTable columns definition.
     *
     * @return array
     */
    public function getColumns(): array
    {
        return [
            Column::make('image')->title('الصورة'),
            Column::make('user.name')->title('الطاهي'),
            Column::make('category.key')->title('التصنيف'),
            Column::make('name')->title('اسم الوجبة'),
            Column::make('code')->title('الكود'),
            Column::make('description')->title('الوصف'),
            Column::make('price')->title('السعر'),
            Column::make('type')->title('النوع'),
            Column::make('is_active')->title('الحالة'),
            Column::make('days')->title('الأيام'),
            Column::make('admin_status')->title('حالة الأدمن'),
            Column::make('admin_note')->title('ملاحظة الأدمن'),
            Column::make('preparation_time')->title('وقت التحضير'),
            Column::make('created_at')->title('تاريخ الإنشاء'),
            Column::computed('action')
                  ->exportable(false)
                  ->printable(false)
                  ->width(60)
                  ->addClass('text-center'),
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename(): string
    {
        return 'Meals_' . date('YmdHis');
    }
}
