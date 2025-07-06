export type User = {
    id: number;
    login: string;
    project: string;
    balance: {
        amount: number;
        currency: string;
    }[];
};

export type Payment = {
    id: number;
    login: string;
    project: string;
    details: string;
    amount: number;
    currency: 'USD' | 'KZT' | 'RUB';
    status: 'created' | 'paid' | 'failed';
};

export type TableFilter = {
    field: string;
    value: string | number | null;
    type: 'input' | 'select';
    options?: string[];
};
