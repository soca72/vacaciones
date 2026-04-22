from fastapi import FastAPI

app = FastAPI(title="Vacaciones API")

@app.get("/health")
def health():
    return {"status": "ok"}