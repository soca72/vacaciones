import oracledb
from .config import settings

pool = None
thick_initialized = False


def init_oracle_thick_mode():
    global thick_initialized

    if thick_initialized:
        return

    # En Linux dentro de contenedor, las librerías deben estar disponibles
    # en la ruta del sistema antes de arrancar Python.
    oracledb.init_oracle_client()
    thick_initialized = True


def create_pool():
    global pool

    if pool is not None:
        return pool

    cfg = settings()
    init_oracle_thick_mode()

    dsn = f"{cfg['oracle_host']}:{cfg['oracle_port']}/{cfg['oracle_service']}"

    pool = oracledb.create_pool(
        user=cfg["oracle_user"],
        password=cfg["oracle_password"],
        dsn=dsn,
        min=cfg["oracle_pool_min"],
        max=cfg["oracle_pool_max"],
        increment=cfg["oracle_pool_increment"],
    )
    return pool


def get_connection():
    if pool is None:
        raise RuntimeError("Oracle pool no inicializado")
    return pool.acquire()


def get_pool_status():
    if pool is None:
        return {
            "initialized": False,
            "thick_initialized": thick_initialized,
        }

    return {
        "initialized": True,
        "thick_initialized": thick_initialized,
        "thin": pool.thin,
        "min": pool.min,
        "max": pool.max,
        "increment": pool.increment,
        "opened": pool.opened,
        "busy": pool.busy,
    }


def close_pool():
    global pool
    if pool is not None:
        pool.close()
        pool = None
