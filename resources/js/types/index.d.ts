import type { User } from './models';

export * from './models';
export * from './responses';

export interface AppLocale {
    code: string;
    label: string;
}

export type PageProps<
    T extends Record<string, unknown> = Record<string, unknown>,
> = T & {
    auth: {
        user: User;
    };
    flash: {
        success?: string;
        error?: string;
        warning?: string;
        info?: string;
    };
    locales: AppLocale[];
};
