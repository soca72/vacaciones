import os


def settings():
    return {
        "oracle_user": os.getenv("ORACLE_USER", ""),
        "oracle_password": os.getenv("ORACLE_PASSWORD", ""),
        "oracle_host": os.getenv("ORACLE_HOST", ""),
        "oracle_port": int(os.getenv("ORACLE_PORT", "1521")),
        "oracle_service": os.getenv("ORACLE_SERVICE", ""),
        "oracle_pool_min": int(os.getenv("ORACLE_POOL_MIN", "1")),
        "oracle_pool_max": int(os.getenv("ORACLE_POOL_MAX", "5")),
        "oracle_pool_increment": int(os.getenv("ORACLE_POOL_INCREMENT", "1")),
    }