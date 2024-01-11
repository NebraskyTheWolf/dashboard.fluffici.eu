import { CustomRequest } from "@riniya.ts/server/base/BaseMiddleware";
import { BaseController } from "@riniya.ts/server/base/BaseController";
import { finish } from "@riniya.ts/types.server";
import { Response } from "express";


class ApiController extends BaseController {

    public async index(request: CustomRequest, response: Response) {
        return finish<{
            message: string
        }>({
            response: response,
            request: {
                code: 200,
                data: {
                    message: "This is a basic route :)"
                }
            }
        });
    }
}

export default new ApiController()