from contextlib import asynccontextmanager
from datetime import date

from fastapi import FastAPI, Query
from fastapi.responses import JSONResponse

from .db_oracle import close_pool, create_pool, get_connection, get_pool_status
from .services.requests_service import (
    buscar_solicitudes,
    estado_es_valido,
    obtener_historial_solicitud,
    tipo_fecha_es_valido,
)
from .services.users_service import (
    buscar_usuarios_por_usuario,
    obtener_argumentos_procedimiento,
)


@asynccontextmanager
async def lifespan(app: FastAPI):
    create_pool()
    yield
    close_pool()


app = FastAPI(title="Vacaciones API", lifespan=lifespan)


@app.get("/health")
def health():
    return {"status": "ok"}


@app.get("/db-check")
def db_check():
    conn = None
    cur = None

    try:
        conn = get_connection()
        cur = conn.cursor()
        cur.execute("select 1 from dual")
        row = cur.fetchone()

        return {
            "status": "ok",
            "database": "oracle",
            "result": row[0] if row else None,
        }

    except Exception as e:
        return JSONResponse(
            status_code=500,
            content={
                "status": "error",
                "database": "oracle",
                "message": str(e),
            },
        )

    finally:
        if cur:
            cur.close()
        if conn:
            conn.close()


@app.get("/oracle/pool-status")
def oracle_pool_status():
    try:
        return {
            "status": "ok",
            "pool": get_pool_status(),
        }

    except Exception as e:
        return JSONResponse(
            status_code=500,
            content={
                "status": "error",
                "endpoint": "/oracle/pool-status",
                "message": str(e),
            },
        )


@app.get("/oracle/procedimiento-args")
def oracle_procedimiento_args(
    nombre: str = Query(..., min_length=3, description="Nombre del procedimiento")
):
    try:
        items = obtener_argumentos_procedimiento(nombre)

        return {
            "status": "ok",
            "nombre": nombre,
            "count": len(items),
            "items": items,
        }

    except Exception as e:
        return JSONResponse(
            status_code=500,
            content={
                "status": "error",
                "endpoint": "/oracle/procedimiento-args",
                "message": str(e),
            },
        )


@app.get("/usuarios/buscar")
def usuarios_buscar(
    q: str = Query(..., min_length=2, description="Usuario a buscar"),
    exacto: bool = Query(
        False,
        description="Si es true, busca coincidencia exacta en usuarios.usuario",
    ),
):
    try:
        items = buscar_usuarios_por_usuario(q, exacto=exacto)

        return {
            "status": "ok",
            "query": q,
            "exacto": exacto,
            "count": len(items),
            "items": items,
        }

    except Exception as e:
        return JSONResponse(
            status_code=500,
            content={
                "status": "error",
                "endpoint": "/usuarios/buscar",
                "message": str(e),
            },
        )


@app.get("/solicitudes/consultar")
def solicitudes_consultar(
    idusuariodata_autorizador: int = Query(
        ...,
        gt=0,
        description="Id de usuariosdata del autorizador que tiene acceso a los departamentos",
    ),
    idsolicitud: int | None = Query(
        None,
        gt=0,
        description="Filtro opcional por id de solicitud exacto",
    ),
    idusuariodata: int | None = Query(
        None,
        gt=0,
        description="Filtro opcional por idusuariodata del solicitante",
    ),
    estado: str | None = Query(
        None,
        description="Filtro opcional: pendiente, aprobada_jefe, autorizada, autorizada_gerente o cancelada",
    ),
    fi: date | None = Query(
        None,
        description="Fecha inicial en formato YYYY-MM-DD",
    ),
    ff: date | None = Query(
        None,
        description="Fecha final en formato YYYY-MM-DD",
    ),
    tipo_fecha: str = Query(
        "permiso",
        description="Tipo de filtro de fecha: permiso o alta",
    ),
    limit: int = Query(
        50,
        ge=1,
        le=200,
        description="Cantidad maxima de resultados",
    ),
):
    try:
        if fi and ff and fi > ff:
            return JSONResponse(
                status_code=400,
                content={
                    "status": "error",
                    "endpoint": "/solicitudes/consultar",
                    "message": "La fecha inicial no puede ser mayor que la fecha final.",
                },
            )

        if not estado_es_valido(estado):
            return JSONResponse(
                status_code=400,
                content={
                    "status": "error",
                    "endpoint": "/solicitudes/consultar",
                    "message": "El parametro estado no es valido. Usa: pendiente, aprobada_jefe, autorizada, autorizada_gerente o cancelada.",
                },
            )

        if not tipo_fecha_es_valido(tipo_fecha):
            return JSONResponse(
                status_code=400,
                content={
                    "status": "error",
                    "endpoint": "/solicitudes/consultar",
                    "message": "El parametro tipo_fecha no es valido. Usa: permiso o alta.",
                },
            )

        items = buscar_solicitudes(
            idusuariodata_autorizador=idusuariodata_autorizador,
            idsolicitud=idsolicitud,
            idusuariodata=idusuariodata,
            estado=estado,
            fi=fi,
            ff=ff,
            tipo_fecha=tipo_fecha,
            limit=limit,
        )

        return {
            "status": "ok",
            "filters": {
                "idusuariodata_autorizador": idusuariodata_autorizador,
                "idsolicitud": idsolicitud,
                "idusuariodata": idusuariodata,
                "estado": estado,
                "fi": fi.isoformat() if fi else None,
                "ff": ff.isoformat() if ff else None,
                "tipo_fecha": tipo_fecha,
                "limit": limit,
            },
            "count": len(items),
            "items": items,
        }

    except Exception as e:
        return JSONResponse(
            status_code=500,
            content={
                "status": "error",
                "endpoint": "/solicitudes/consultar",
                "message": str(e),
            },
        )


@app.get("/solicitudes/detalle")
def solicitudes_detalle(
    idsolicitud: int = Query(
        ...,
        gt=0,
        description="Id de la solicitud para consultar su historial detallado",
    ),
):
    try:
        items = obtener_historial_solicitud(idsolicitud)

        return {
            "status": "ok",
            "idsolicitud": idsolicitud,
            "count": len(items),
            "items": items,
        }

    except Exception as e:
        return JSONResponse(
            status_code=500,
            content={
                "status": "error",
                "endpoint": "/solicitudes/detalle",
                "message": str(e),
            },
        )
