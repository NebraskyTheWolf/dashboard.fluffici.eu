use crate::config::Config;
use actix_web::HttpResponse;
use serde_json::json;

pub async fn get() -> HttpResponse {
    let config = Config::global();
    let body = json!({
        "autumn": crate::version::VERSION,
        "revision": *crate::util::variables::GIT_COMMIT,
        "tags": config.tags,
        "jpeg_quality": config.jpeg_quality
    });

    HttpResponse::Ok().json(body)
}

pub async fn get_health() -> HttpResponse { 
    let body = json!({
        "status": "ok"
    });

    HttpResponse::Ok().json(body)
}