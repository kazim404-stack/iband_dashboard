<?php

namespace App\DataTables;

use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Illuminate\Validation\Rules\Can;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class OrdersDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder<Order> $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('action', function ($query) {
                return '<div class="d-flex justify-content-between ">
            <a href="' . route('admin.orders.delete', $query->id) . '" class="btn btn-danger btn-md" id="confirmation" data-datatable_id="#orders-table"><i class="fas fa-trash" ></i></a>
            </div>';
            })->addColumn('by', function ($query) {
                return $query->user  ? $query->user->name : '';
            })->addColumn('discount', function ($query) {
                return $query->coupon  ? '<span class="badge bg-warning p-1 text-white">' . '%' . $query->coupon->discount . '</span>' : '<span class="badge bg-danger p-1 text-white">' . '%' . 0 . '</span>';
            })->addColumn('product_name', function ($order) {
                return $order->items->map(function ($item) {
                    return $item->productVariant ? $item->productVariant->product->name : '';
                })->filter()->implode(', ');
            })->addColumn('product_image', function ($order) {
                return $order->items->map(function ($item) {
                    $primaryImage = $item->productVariant->product->productImages->firstWhere('is_primary', true);
                    if ($primaryImage) {
                        return '<div class = "d-flex column-reverse">
                        <img class="my-1" src="' . asset($primaryImage->image) . '" alt="' . $primaryImage->alt_text . '" width="80">
                        </div>';
                    }
                    return '';
                })->implode(' ');
            })->addColumn('delivered_at', function ($query) {
                return $query->delivered_at === null  ? '<a id="updateDeliveredAtDate"
                 href="'.route('admin.orders.updateDeliveredAtDate',$query->id).'" >
                 <i class="fas fa-pen"></i></a>' : '<span class="badge bg-success p-2 text-white">' . Carbon::parse($query->delivered_at)->diffForHumans() . '</span>';
            })
            ->rawColumns(['action', 'discount', 'product_image', 'delivered_at'])
            ->setRowId('id');
    }

    /**
     * Get the query source of dataTable.
     *
     * @return QueryBuilder<Order>
     */
    public function query(Order $model): QueryBuilder
    {
        return $model->newQuery();
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('orders-table')
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
            Column::make('product_image'),
            Column::make('by'),
            Column::make('discount'),
            Column::make('total_price'),
            Column::make('delivered_at'),
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
        return 'Orders_' . date('YmdHis');
    }
}
