import BaseMiddleware, { CustomRequest } from "@riniya.ts/server/base/BaseMiddleware"
import { isNull } from "@riniya.ts/types"
import { NextFunction, Response } from "express"

class Authentication extends BaseMiddleware {
    public async handle(request: CustomRequest, response: Response, next: NextFunction) {
        const token = request.header("Authorization")?.replace("Bearer ", "");

        if (isNull(token))
            return response.status(403).json({
                status: false,
                error: "AUTHENTICATION_REQUIRED",
                message: "Please authenticate to use this resources."
            }).end();

        if (token == "<token>") {
            return response.status(403).json({
                status: false,
                error: "AUTHENTICATION_REQUIRED",
                message: "Please authenticate to use this resources."
            }).end();
        }

        /**
         * 
        if (!isNull(player.uuid)) {
            if (player.security.isTerminated) {
                return response.status(403).json({
                    status: false,
                    error: "BANNED_ACCOUNT",
                    message: "Your account has been banned permanently."
                }).end();
            }

            request.token = player._id;
            return next()
        } else {
            return response.status(500).json({
                status: false,
                error: "Invalid account data or signature does not match."
            }).end();
        }
         */
    }
}

export default new Authentication()