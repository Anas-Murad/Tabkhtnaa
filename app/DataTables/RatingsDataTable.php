<?php

namespace App\DataTables;

use App\Models\Rating;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class RatingsDataTable extends DataTable
{
    /**
     * Build DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     * @return \Yajra\DataTables\EloquentDataTable
     */
    protected $user;
    public function __construct($user)
    {
        $this->user =$user;
    }

    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->editColumn('photo' , '<img style=" width: 50px; height: 50px; " src="{{asset($photo ?? "assets/images/demo/users/face11.jpg")}}">')
            ->editColumn('note', function ($rating) {
                if (!$rating->note) return  '-';
                return "<a class='btn btn-link' onclick='showNote(`$rating->note`)'> ". str($rating->note)->limit(50). " </a>";
            })
            ->editColumn('created_at' , function ($rating){
                return $rating->created_at->toDateString();
            })
            ->editColumn('updated_at' , function ($rating){
                return $rating->updated_at->toDateString();
            })
            ->editColumn('action', function ($rating) {
                $ShowOrder = (route('admin.orders.show',$rating->order->id));
                $ShowUser = (route('users.show',$rating->user_id));
                $ShowChef = (route('users.show',$rating->chef_id));
                return <<<HTML
                <div class="d-inline-flex">
                        <div class="dropdown">
                            <a href="#" class="text-body" data-bs-toggle="dropdown">
                                <i class="ph-list"></i>
                            </a>
                        <div class="dropdown-menu dropdown-menu-end">
                            <a href="$ShowOrder" class="dropdown-item" > <i class="ph-eye me-2"></i> Show Order </a>
                            <a href="$ShowUser" class="dropdown-item" > <i class="ph-eye me-2"></i> Show User </a>
                            <a href="$ShowChef" class="dropdown-item" > <i class="ph-eye me-2"></i> Show Chef </a>
                        </div>
                    </div>
                </div>
                HTML;
            })
            ->setRowId('id')
            ->rawColumns([
                'action',
                'photo',
                'note',
                'created_at',
                'updated_at',
            ]);
            ;

    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\Rating $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Rating $model): QueryBuilder
    {
        return $model->newQuery()
            ->when($this->user , function ($q){
                $type = $this->user->type;
               switch ($type)
               {
                   case 'client' : $q->where('user_id' , $this->user->id);
                       break;
                   case 'delivery' : $q->where('delivery_id' , $this->user->id);
                       break;
                   case 'chef' : $q->where('chef_id' , $this->user->id);
                       break;
               }
            })
            ->with([
                'user' => function($q){
                  $q->select('id' , 'name');
                },
                'chef' => function($q1){
                  $q1->select('id' , 'name');
                },
                'order' => function($q1){
                  $q1->select('id');
                }
            ])
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
                $q->when($this->request->filled('gender'), function ($q) {
                    $q->where('gender', $this->request->input('gender'));
                });
                $q->when($this->request->filled('country_id') || $this->request->filled('city_id'), function ($q) {
                    $r = [];
                    if ($this->request->filled('country_id')) $r['country_id'] = $this->request->country_id;
                    if ($this->request->filled('city_id')) $r['city_id'] = $this->request->country_id;
                    $q->whereRelation('userAddress', $r);
                });
            })
            ->when($this->request->filled('from_date'), function ($q) {
                $q->where('created_at', '>=', $this->request()->input('from_date'));
            })
            ->when($this->request->filled('to_date'), function ($q) {
                $q->where('created_at', '<=', $this->request()->input('to_date'));
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
                    ->orderBy(8)
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
            Column::make('photo')->title('Photo'),
            Column::make('user.name')->title('User Name'),
            Column::make('chef.name')->title('Chef Name'),
            Column::make('rating_chef')->title('Rating Chef')
                ->visible($this->user->type != 'delivery'),
            Column::make('rating_speed_chef')->title('Rating Speed Chef')
                ->visible($this->user->type != 'delivery'),
            Column::make('rating_delivery')->title('Rating Delivery')
                ->visible($this->user->type != 'chef'),
            Column::make('rating_speed_delivery')->title('Rating Speed Delivery')
                ->visible($this->user->type != 'chef'),
            Column::make('note')->title('Note'),
            Column::make('created_at'),
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
        return 'Ratings_' . date('YmdHis');
    }
}
