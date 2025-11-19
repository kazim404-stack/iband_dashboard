<?php

namespace App\DataTables;

use App\Models\Coupon;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class CouponsDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder<Coupon> $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('action', function ($query) {
                return '<div class="d-flex justify-content-between ">
            <a href="' . route('admin.coupons.edit', $query->id) . '"  class="btn btn-primary btn-md edit-coupon-btn" data-bs-toggle="modal" data-bs-target="#edit-coupon" style="margin-right:4px;"><i class="fas fa-edit"></i></a>
            <a href="' . route('admin.coupons.destroy', $query->id) . '" class="btn btn-danger btn-md" id="confirmation" data-datatable_id="#coupons-table"><i class="fas fa-trash" ></i></a>
            </div>';
            })->addColumn('discount', function ($query) {
                return $query->discount > 0 ? '<span class="badge bg-success text-white p-2">' . '%' . $query->discount . '</span>' : '';
            })->addColumn('valid_until', function ($query) {
                return $query->checkIfValid() ? '<span class="badge bg-success text-white p-2">' . Carbon::parse($query->valid_until)->diffForHumans() . '</span>' : '<span class="badge bg-danger text-white p-2">Expired...</span>';
            })
            ->rawColumns(['action', 'discount', 'valid_until'])
            ->setRowId('id');
    }

    /**
     * Get the query source of dataTable.
     *
     * @return QueryBuilder<Coupon>
     */
    public function query(Coupon $model): QueryBuilder
    {
        return $model->newQuery();
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('coupons-table')
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
            Column::make('name'),
            Column::make('discount'),
            Column::make('valid_until'),
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
        return 'Coupons_' . date('YmdHis');
    }
}
