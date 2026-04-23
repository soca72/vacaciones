FROM python:3.11-slim-bookworm

RUN apt-get update && apt-get install -y \
    unzip \
    libaio1 \
    && rm -rf /var/lib/apt/lists/*

WORKDIR /app

COPY requirements.txt .
RUN pip install --no-cache-dir -r requirements.txt

# Oracle Instant Client
WORKDIR /opt/oracle
COPY oracle/instantclient-basic.zip /tmp/instantclient-basic.zip
RUN unzip /tmp/instantclient-basic.zip -d /opt/oracle \
    && rm /tmp/instantclient-basic.zip \
    && ln -s /opt/oracle/instantclient_* /opt/oracle/instantclient \
    && echo /opt/oracle/instantclient > /etc/ld.so.conf.d/oracle-instantclient.conf \
    && ldconfig

ENV LD_LIBRARY_PATH=/opt/oracle/instantclient
ENV PATH=/opt/oracle/instantclient:$PATH

WORKDIR /app
COPY app ./app

CMD ["uvicorn", "app.main:app", "--host", "0.0.0.0", "--port", "8000"]