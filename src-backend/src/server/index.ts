import * as dotenv from "dotenv";
dotenv.config()

global.__rootdir__ = __dirname || process.cwd();

declare global {
    var __rootdir__: string;
}

import 'module-alias/register';

import Environement from "@riniya.ts/server/utils/Environement";
import BaseRoute from "@riniya.ts/server/base/BaseRoute";
import APIRoutes from "@riniya.ts/server/routes/api.routes";
import { Int, Str } from "@riniya.ts/types";
import cors from "cors";
import RateLimit from "express-rate-limit"
import express, { Request, Response } from "express"
import session from "express-session"
import bodyParser, * as parser from "body-parser"
import http from "http";
import fileUpload from "express-fileupload";

import mysql from "mysql";

import cookieParser from "cookie-parser";
import { v4 } from "uuid";

const app = express();
const limiter = RateLimit({
    windowMs: 1 * 60 * 1000,
    max: 10,
    message: {
        status: false,
        error: "Too many request."
    }
})
app.use(limiter)
app.use(parser.json())
app.use(bodyParser.json())

app.use(cookieParser(v4()))

app.use(fileUpload({
    limits: { fileSize: 50 * 1024 * 1024 },
    abortOnLimit: true,
    limitHandler: (request: Request, response: Response) => {
        response.status(400).json({
            status: false,
            error: "FILE_TOO_BIG",
            message: "The file is too big."
        })
    },
}));

export default class ServerManager {

    public static instance: ServerManager

    private routes: Map<Int, BaseRoute> = new Map<Int, BaseRoute>()
    private server: http.Server

    public readonly environement: Environement

    private readonly version: String
    private readonly revision: String

    public readonly mysql: mysql.Connection;

    public constructor() {
        ServerManager.instance = this
        this.environement = new Environement()
        this.environement.catch<Error>()

        this.mysql = mysql.createConnection({
            host: this.environement.read<string>("DB_HOST"),
            user: this.environement.read<string>("DB_USERNAME"),
            password: this.environement.read<string>("DB_PASSWORD"),
            database: this.environement.read<string>("DB_DATABASE")
        }); 

        this.mysql.connect()

        this.version = this.environement.read<Str>("VERSION") || "No version set."
        this.revision = this.environement.read<Str>("GIT_COMMIT") || "No revision set."

        if (this.environement.init()) {
            console.error("-> Failed to setup the configuration.")
        } else {
            console.log("-> Configuration loaded.")
            console.log(`-> Version : ${this.version}`)
            console.log(`-> Revision : ${this.revision}`)

            this.routes.set(0, new APIRoutes())

            this.startApp()
        }
    }

    public startApp() {
        app.set("trust proxy", 1)
        app.use(cors({
            origin: this.environement.read<string>("CORS_ALLOWED_ORIGINS"),
            methods: this.environement.read<string>("CORS_ALLOWED_METHODS")
        }))

        app.use(session({
            secret: this.environement.read<string>("APP_KEY"),
            resave: false,
            saveUninitialized: true,
            cookie: {
                secure: true
            }
        }))

        this.server = http.createServer(app)

        app.get('/', (req: Request, res: Response) => {
            return res.status(200).json({
                appName: 'Fluffici RESTFul API',
                appVersion: this.version,
                appRevision: this.revision,
                appAuthors: [
                    "NebraskyTheWolf <farfy.dev@gmail.com>"
                ]
            }).end()
        })

        this.routes.forEach(x => {
            console.log(`[+] Registering router : ${x}`)
            app.use('/api', x.routing())
        })

        this.server.listen(this.environement.read<Int>("PORT") || 3659)
    }

    public static getInstance(): ServerManager {
        return this.instance
    }
}

export const serverManager: ServerManager = new ServerManager()