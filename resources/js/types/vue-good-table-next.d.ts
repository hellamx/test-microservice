declare module 'vue-good-table-next' {
    import { DefineComponent } from 'vue';

    export interface Column {
        label: string;
        field: string;
        type?: 'number' | 'boolean' | 'date' | 'percentage';
        formatFn?: (value: any) => string;
        width?: string;
        tdClass?: string;
        headerSlotName?: string;
        thClass?: string;
        sortable?: boolean;
    }

    export interface SortOptions {
        enabled: boolean;
        initialSortBy?: {
            field: string;
            type: 'asc' | 'desc';
        };
    }

    export interface SearchOptions {
        enabled: boolean;
        placeholder?: string;
        externalQuery?: string;
    }

    export interface SelectOptions {
        enabled: boolean;
        selectOnCheckboxOnly?: boolean;
        selectionText?: string;
        clearSelectionText?: string;
    }

    export interface PaginationOptions {
        enabled: boolean;
        perPage?: number;
        perPageDropdown?: number[];
    }

    const VueGoodTable: DefineComponent<{
        columns: Column[];
        rows: any[];
        sortOptions?: SortOptions;
        searchOptions?: SearchOptions;
        selectOptions?: SelectOptions;
        paginationOptions?: PaginationOptions;
    }>;

    export default VueGoodTable;

    export class VueGoodTable {
    }
}
