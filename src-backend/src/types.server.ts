import { Response } from "express";

export interface Base<T> {
    response: Response,
    request: T
}

export interface ErrorOption {
    code: number;
    error: string;
    message: string;
} 

export interface ResultOption<T> {
    code: number;
    data: T
} 

/**
 * @description Sending the error message
 */
export function throwError(req: Base<ErrorOption>) {
    return req.response.status(req.request.code).json({
        status: false, 
        error: req.request.error,
        message: req.request.message
    }).end();
}

 /**
  * @description Ending a request with the collected data.
  */
export function finish<T>(req: Base<ResultOption<T>>) {
    return req.response.status(req.request.code).json({
        status: true, 
        data: req.request.data
    });
}