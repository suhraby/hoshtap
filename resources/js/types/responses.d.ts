export type LocaleKey = 'en' | 'ru' | 'tm';

interface Locale {
    code: LocaleKey;
    label: string;
}

export interface LocalizedText {
    en: string;
    ru: string;
    tm: string;
    [key: string]: string;
}

export interface PaginationLink {
    url: string | null;
    label: string;
    page: number | null;
    active: boolean;
}

export interface PaginationMeta {
    current_page: number;
    from: number | null;
    last_page: number;
    links: PaginationLink[];
    path: string;
    per_page: number;
    to: number | null;
    total: number;
    next_page_url: string;
}

export interface PaginationUrls {
    first: string;
    last: string;
    prev: string | null;
    next: string | null;
}

export interface PaginatedResponse<T> {
    data: T[];
    links: PaginationUrls;
    meta: PaginationMeta;
}

export interface ResourceResponse<T> {
    data: T;
}
