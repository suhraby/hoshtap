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
    [key: string]: unknown;
}

export interface About {
    id: number;
    title: LocalizedText;
    body: LocalizedText;
    image: string;
}
