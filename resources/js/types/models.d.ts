import { LocalizedText } from './responses';

export interface User {
    id: number;
    name: string;
    surname: string;
    username: string;
    email_verified_at?: string;
    created_at: string;
}

export interface Banner {
    id: number;
    title: LocalizedText;
    image: string;
    thumbnail: string;
    original: string;
    sort_order: number;
    [key: string]: unknown;
}

export interface About {
    id: number;
    title: LocalizedText;
    market_title: LocalizedText;
    body: LocalizedText;
    image: string;
    market_image: string;
}

export interface Counter {
    id: number;
    title: LocalizedText;
    description: LocalizedText;
    number: number;
    symbol: string | null;
    sort_order: number;
    [key: string]: unknown;
}

export interface Service {
    id: number;
    title: LocalizedText;
    description: LocalizedText;
    icon: string;
    sort_order: number;
    [key: string]: unknown;
}

export interface Product {
    id: number;
    title: LocalizedText;
    image: string;
    sort_order: number;
    [key: string]: unknown;
}

export interface Manufacturer {
    id: number;
    title: LocalizedText;
    image: string;
    [key: string]: unknown;
}

export interface Client {
    id: number;
    title: LocalizedText;
    image: string;
    sort_order: number;
    [key: string]: unknown;
}

export interface Contact {
    id: number;
    icon: string;
    slug: string;
    value: LocalizedText | string;
    locale_value: LocalizedText;
    is_active: boolean;
    [key: string]: unknown;
}
