<?php

namespace App\DataTables;

use App\Models\LoyaltyTransaction;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class LoyaltyTransactionsDataTable extends DataTable
{
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('user_name', fn ($row) => $row->user?->name ?? '-')
            ->editColumn('points', function ($row) {
                $color = $row->points >= 0 ? 'text-success' : 'text-danger';

                return "<span class='{$color} fw-bold'>{$row->points}</span>";
            })
            ->editColumn('type', fn ($row) => "<span class='badge bg-light text-dark'>{$row->type}</span>")
            ->editColumn('created_at', fn ($row) => $row->created_at?->format('Y-m-d H:i'))
            ->rawColumns(['points', 'type'])
            ->setRowId('id');
    }

    public function query(LoyaltyTransaction $model): QueryBuilder
    {
        $query = $model->newQuery()->with('user:id,name')->latest();

        if ($type = $this->request->get('type')) {
            $query->where('type', $type);
        }
        if ($userId = $this->request->get('user_id')) {
            $query->where('user_id', (int) $userId);
        }

        return $query;
    }

    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('data-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->ajaxWithForm(url()->current(), '#loyaltyTransactionsFilter')
            ->orderBy(0, 'desc');
    }

    public function getColumns(): array
    {
        return [
            Column::make('id'),
            Column::make('user_name')->title('العميل')->orderable(false),
            Column::make('points')->title('النقاط'),
            Column::make('type')->title('النوع'),
            Column::make('description')->title('الوصف'),
            Column::make('order_id')->title('الطلب'),
            Column::make('created_at')->title('التاريخ'),
        ];
    }

    protected function filename(): string
    {
        return 'LoyaltyTransactions_' . date('YmdHis');
    }
}
