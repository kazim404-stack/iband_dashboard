<?php

namespace App\DataTables;

use App\Models\Review;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class ReviewsDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder<Review> $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('action', function ($query) {
                if ($query->approved == 1) {
                    $approvedBtn = '<a class="badge bg-warning p-2 toggleReviewStatus" href="' . route('admin.reviews.toggleReviewStatus', ['review' => $query->id, 'status' => 0]) . '"><i class="fas fa-eye-slash"></i></a>';
                } else {
                    $approvedBtn = '<a class="badge bg-success p-2 toggleReviewStatus" href="' . route('admin.reviews.toggleReviewStatus', ['review' => $query->id, 'status' => 1]) . '"><i class="fas fa-check-double"></i></a>';
                }
                return '<div class="d-flex justify-content-between ">
                ' . $approvedBtn . '
            <a href="' . route('admin.reviews.delete', $query->id) . '" class="btn btn-danger btn-md ms-1" id="confirmation" data-datatable_id="#reviews-table"><i class="fas fa-trash" ></i></a>
            </div>';
            })->addColumn('by',function($query){
                return $query->user ? $query->user->name: '';

            })->addColumn('product_name',function($query){
                return $query->productVariant->product ? $query->productVariant->product->name: '';

            })->addColumn('status',function($query){
                return $query->approved == 1 ? '<span class="badge bg-success text-white p-2">Yes</span>': '<span class="badge bg-danger text-white p-2">No</span>';

            })->addColumn('create_at',function($query){
                return Carbon::parse($query->created_at)->diffForHumans();

            })
            ->rawColumns(['action','status'])
            ->setRowId('id');
    }

    /**
     * Get the query source of dataTable.
     *      *
     * @return QueryBuilder<Review>
     */
    public function query(Review $model): QueryBuilder
    {
        return $model->newQuery()->orderBy('id', 'desc');
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('reviews-table')
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
            Column::make('by'),
            Column::make('product_name'),
            Column::make('title'),
            Column::make('body'),
            Column::make('rating'),
            Column::make('status'),
            Column::make('created_at'),
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
        return 'Reviews_' . date('YmdHis');
    }
}
