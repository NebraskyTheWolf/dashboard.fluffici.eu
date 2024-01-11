import { isNull } from "@riniya.ts/types";

export default class Environement {
    public init(): Boolean {
        if (this.unset("DB_HOST"))
            return this.print("DB_HOST")
        if (this.unset("DB_USERNAME"))
            return this.print("DB_USERNAME")
        if (this.unset("DB_PASSWORD"))
            return this.print("DB_PASSWORD")
        if (this.unset("DB_DATABASE"))
            return this.print("DB_DATABASE")
        else if (this.unset("CORS_ALLOWED_ORIGINS"))
            return this.print("CORS_ALLOWED_ORIGINS")
        else if (this.unset("CORS_ALLOWED_METHODS"))
            return this.print("CORS_ALLOWED_METHODS")
        else if (this.unset("PORT"))
            return this.print("PORT")
        else if (this.unset("ENVIRONEMENT"))
            return this.print("ENVIRONEMENT")
        return false
    }

    public unset(key: string): Boolean {
        return isNull(process.env[key])
    }

    public read<T>(key: string): T {
        if (this.unset(key))
            this.print(key)
        return process.env[key] as T
    }

    public catch<T>() {
        process.on('uncaughtException', function (error: Error) {
            console.error(`-> 'uncaughtException' : ${error.message} : ${error.cause}`)
        })
        process.on('unhandledRejection', function (error: Error) {
            console.error(`-> 'unhandledRejection' : ${error.stack}`)
        })
    }

    private print(key: string): Boolean {
        console.error("-------------------------------------------")
        console.error(" -> Environement failed at '" + key + "'.  ")
        console.error("   -> Please check your environement file. ")
        console.error("   -> Restart is required to continue.     ")
        console.error("-------------------------------------------")
        return true;
    }
}