<?php

namespace App\DataTables;

use App\Models\Stock;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class StocksDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder<Stock> $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('action', function ($query) {
                return '<div class="d-flex justify-content-between ">
            <a href="' . route('admin.stock.edit', $query->id) . '"  class="btn btn-primary btn-md edit-stock-btn" data-bs-toggle="modal" data-bs-target="#edit-stock" style="margin-right:4px;"><i class="fas fa-edit"></i></a>
            <a href="' . route('admin.stock.destroy', $query->id) . '" class="btn btn-danger btn-md" id="confirmation" data-datatable_id="#stocks-table"><i class="fas fa-trash" ></i></a>
            </div>';
            })->addColumn('product_name', function ($query) {
                return $query->productVariant->product ? $query->productVariant->product->getTranslation('name', 'en') : '';
            })->addColumn('sku', function ($query) {
                return $query->productVariant ? $query->productVariant->sku : '';
            })->addColumn('stock_status', function ($query) {
                return $query->stock_status == 'in_stock' ? '<span class="badge bg-success text-white">in_stock</span>' : '<span class="badge bg-warning text-white">out_of_stock</span>';
            })->addColumn('currency_code', function ($query) {
                return $query->currency->code && $query->productVariant->price ? $query->productVariant->price . ' ' . $query->currency->code : '';
            })->addColumn('attribute', function ($query) {
                return $query->productVariant->attributeValues
                    ->map(fn($val) => $val->getTranslation('value', 'en'))
                    ->implode(', ');
            })
            ->rawColumns(['action', 'stock_status'])
            ->setRowId('id');
    }

    /**
     * Get the query source of dataTable.
     *
     * @return QueryBuilder<Stock>
     */
    public function query(Stock $model): QueryBuilder
    {
        return $model->newQuery();
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('stocks-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->orderBy(1)
            ->selectStyleSingle()
            ->buttons([
                Button::make('excel'),
                Button::make('csv'),
                Button::make('pdf'),
                Button::make('print'),
                Button::make('reset'),
                Button::make('reload')
            ]);
    }

    /**
     * Get the dataTable columns definition.
     */
    public function getColumns(): array
    {
        return [

            Column::make('id'),
            Column::make('product_name'),
            Column::make('currency_code'),
            Column::make('sku'),
            Column::make('attribute'),
            Column::make('qty'),
            Column::make('stock_status'),
            Column::computed('action')
                ->exportable(false)
                ->printable(false)
                ->width(60)
                ->addClass('text-center'),
        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'Stocks_' . date('YmdHis');
    }
}
