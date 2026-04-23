from contextlib import asynccontextmanager
from datetime import date

from fastapi import FastAPI, Query
from fastapi.responses import JSONResponse

from .db_oracle import create_pool, get_connection, close_pool
from .services.requests_service import buscar_solicitudes, obtener_historial_solicitud
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
        items = buscar_solicitudes(
            idusuariodata_autorizador=idusuariodata_autorizador,
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
