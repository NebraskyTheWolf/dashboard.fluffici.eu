import express, { Router } from "express";

export default abstract class BaseRoute { 
    protected router: Router
    private protected: Boolean

    public constructor() {
        this.router = router;
        this.protected = false;

        this.register()
    }

    public abstract register(): void

    public routing(): Router {
        return router;
    }

    protected setProtected(val: Boolean) {
        this.protected = val;
    }

    public isProtected(): Boolean {
        return this.protected
    }
}

export const router = express.Router()