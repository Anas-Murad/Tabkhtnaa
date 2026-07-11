<?php

namespace App\DataTables;

use App\Models\LoyaltyTransaction;
use App\Support\LoyaltyLabels;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class LoyaltyTransactionsDataTable extends DataTable
{
    protected ?string $filterType = null;

    protected ?int $filterUserId = null;

    public function __construct(?string $filterType = null, ?int $filterUserId = null)
    {
        $this->filterType = $filterType;
        $this->filterUserId = $filterUserId;
    }

    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('user_name', function ($row) {
                if (!$row->user) {
                    return '-';
                }

                $url = route('users.show', $row->user_id);

                return "<a href='{$url}'>{$row->user->name}</a>";
            })
            ->editColumn('points', function ($row) {
                $color = $row->points >= 0 ? 'text-success' : 'text-danger';

                return "<span class='{$color} fw-bold'>{$row->points}</span>";
            })
            ->editColumn('type', fn ($row) => '<span class="badge bg-light text-dark">'
                . e(LoyaltyLabels::transactionType($row->type)) . '</span>')
            ->editColumn('order_id', fn ($row) => $row->order_id
                ? '<a href="' . route('admin.orders.show', $row->order_id) . '">#' . $row->order_id . '</a>'
                : '-')
            ->editColumn('created_at', fn ($row) => $row->created_at?->format('Y-m-d H:i'))
            ->rawColumns(['user_name', 'points', 'type', 'order_id'])
            ->setRowId('id');
    }

    public function query(LoyaltyTransaction $model): QueryBuilder
    {
        $query = $model->newQuery()->with('user:id,name')->latest();

        $type = $this->filterType ?: $this->request->get('type');
        $userId = $this->filterUserId ?: ($this->request->filled('user_id') ? (int) $this->request->get('user_id') : null);

        if ($type) {
            $query->where('type', $type);
        }
        if ($userId) {
            $query->where('user_id', $userId);
        }

        return $query;
    }

    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('data-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->ajaxWithForm(url()->current(), '#filter_form')
            ->orderBy(0, 'desc')
            ->buttons([
                Button::make('excel')->className('btn btn-dark')->text('<i class="ph-microsoft-excel-logo"></i> Excel'),
                Button::make('csv')->className('btn btn-info')->text('<i class="ph-file-csv"></i> CSV'),
                Button::make('print')->className('btn btn-success')->text('<i class="ph-printer"></i> طباعة'),
            ]);
    }

    public function getColumns(): array
    {
        return [
            Column::make('id')->title('#'),
            Column::make('user_name')->title('العميل')->orderable(false)->searchable(false),
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
