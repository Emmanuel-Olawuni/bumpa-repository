export type * from './auth';
export type * from './navigation';
export type * from './ui';

import type { Auth } from './auth';

export type SharedData = {
    name: string;
    auth: Auth;
    sidebarOpen: boolean;
    [key: string]: unknown;
};
export interface User {
    id: number;
    name: string;
    email: string;
    email_verified_at: string;
}

export interface Merchant {
    id: number;
    name: string;
    slug: string;
    email: string;
    phone: string;
    logo: string;
    description?: string;
}

export interface Product {
    id: number;
    merchant_id: number;
    merchant: Merchant;
    name: string;
    slug: string;
    description: string;
    price: number;
    stock: number;
    image: string;
    category: string;
    is_active: boolean;
}

export interface CartItem {
    id: number;
    product_id: number;
    product: Product;
    quantity: number;
    price_at_add: number;
    subtotal: number;
}

export type PageProps<
    T extends Record<string, unknown> = Record<string, unknown>,
> = T & {
    auth: {
        user: User;
    };
    cart?: {
        items_count: number;
        total: number;
    };
    flash?: {
        success?: string;
        error?: string;
    };
};
