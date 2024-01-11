export declare type Str = String | string;
export declare type Int = Number | number;
export declare type Bool = Boolean | boolean;
export declare type Result = {
    status: boolean;
    error: string;
}

/**
 * @param object The object to check.
 * @description Checking is a object is null.
 */
export function isNull(object: unknown): Boolean {
    if (object === null || object === undefined)
        return true
    return false
}

/**
 * @param object The object to check.
 * @description Checking is a object is null and then checking is the type is the same. 
 */
export function isTypeNull<T>(object: unknown): Boolean {
    if (object === null || object === undefined || !(object as T))
        return true
    return false
}