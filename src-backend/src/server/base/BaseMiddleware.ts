import { Request, Response, NextFunction } from "express";

export interface CustomRequest extends Request {
    token?: string
}

export default abstract class BaseMiddleware {
    public abstract handle(request: CustomRequest, response: Response, next: NextFunction): void
}