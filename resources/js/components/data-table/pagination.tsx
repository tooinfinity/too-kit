import { Button } from '@/components/ui/button';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import { Table } from '@tanstack/react-table';
import { ChevronLeft, ChevronRight, ChevronsLeft, ChevronsRight } from 'lucide-react';

interface PaginationProps<TData> {
    table: Table<TData>;
    totalRows?: number;
    onPageChange?: (pageIndex: number) => void;
    onPerPageChange?: (pageSize: number) => void;
}

export default function Pagination<TData>({ table, totalRows, onPageChange, onPerPageChange }: PaginationProps<TData>) {
    const totalRowCount = totalRows || table.getFilteredRowModel().rows.length;
    const pageIndex = table.getState().pagination.pageIndex;
    const pageSize = table.getState().pagination.pageSize;

    const handlePageChange = (newPageIndex: number) => {
        if (onPageChange) {
            onPageChange(newPageIndex);
        } else {
            table.setPageIndex(newPageIndex);
        }
    };

    const handlePerPageChange = (value: string) => {
        const newPageSize = Number(value);

        if (onPerPageChange) {
            onPerPageChange(newPageSize);
        } else {
            table.setPageSize(newPageSize);
        }
    };

    return (
        <div className="mt-6 mb-10 flex items-center justify-between">
            <div className="flex-1 text-sm">Total {totalRowCount} rows.</div>
            <div className="flex items-center space-x-6 lg:space-x-8">
                <div className="flex items-center space-x-2">
                    <p className="text-sm font-medium">Rows per page</p>
                    <Select value={`${pageSize}`} onValueChange={handlePerPageChange}>
                        <SelectTrigger className="h-8 w-[70px]">
                            <SelectValue placeholder={pageSize} />
                        </SelectTrigger>
                        <SelectContent side="top">
                            {[10, 20, 30, 40, 50].map((size) => (
                                <SelectItem key={size} value={`${size}`}>
                                    {size}
                                </SelectItem>
                            ))}
                        </SelectContent>
                    </Select>
                </div>
                <div className="flex w-[100px] items-center justify-center text-sm font-medium">
                    Page {pageIndex + 1} of {Math.max(table.getPageCount(), 1)}
                </div>
                <div className="flex items-center space-x-2">
                    <Button
                        variant="outline"
                        className="hidden h-8 w-8 p-0 lg:flex"
                        onClick={() => handlePageChange(0)}
                        disabled={!table.getCanPreviousPage()}
                    >
                        <span className="sr-only">Go to first page</span>
                        <ChevronsLeft />
                    </Button>
                    <Button
                        variant="outline"
                        className="h-8 w-8 p-0"
                        onClick={() => handlePageChange(pageIndex - 1)}
                        disabled={!table.getCanPreviousPage()}
                    >
                        <span className="sr-only">Go to previous page</span>
                        <ChevronLeft />
                    </Button>
                    <Button
                        variant="outline"
                        className="h-8 w-8 p-0"
                        onClick={() => handlePageChange(pageIndex + 1)}
                        disabled={!table.getCanNextPage()}
                    >
                        <span className="sr-only">Go to next page</span>
                        <ChevronRight />
                    </Button>
                    <Button
                        variant="outline"
                        className="hidden h-8 w-8 p-0 lg:flex"
                        onClick={() => handlePageChange(table.getPageCount() - 1)}
                        disabled={!table.getCanNextPage()}
                    >
                        <span className="sr-only">Go to last page</span>
                        <ChevronsRight />
                    </Button>
                </div>
            </div>
        </div>
    );
}
