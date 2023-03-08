#FROM mono:latest
FROM mcr.microsoft.com/dotnet/runtime:6.0-bullseye-slim
RUN apt-get update
RUN apt-get install -y wget

ENV DOCKERIZE_VERSION v0.6.1
RUN wget https://github.com/jwilder/dockerize/releases/download/$DOCKERIZE_VERSION/dockerize-alpine-linux-amd64-$DOCKERIZE_VERSION.tar.gz \
    && tar -C /usr/local/bin -xzvf dockerize-alpine-linux-amd64-$DOCKERIZE_VERSION.tar.gz \
    && rm dockerize-alpine-linux-amd64-$DOCKERIZE_VERSION.tar.gz


COPY akiled /app
WORKDIR /app/

CMD ["dotnet", "AkiledEmulator.dll"]