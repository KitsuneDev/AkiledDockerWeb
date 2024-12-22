FROM node:lts as builder

ARG BRANCH=dev
ARG COMMIT=7ff2405

WORKDIR /app

RUN apt-get update && apt-get install -y \
        git \
        build-essential \
        python3 \
        libcairo2-dev \
        libpango1.0-dev \
        libjpeg-dev \
        libgif-dev \
        librsvg2-dev \
    && rm -rf /var/lib/apt/lists/*
RUN apt remove apt --autoremove -y --allow-remove-essential

ENV DOCKERIZE_VERSION v0.6.1
RUN wget https://github.com/jwilder/dockerize/releases/download/$DOCKERIZE_VERSION/dockerize-alpine-linux-amd64-$DOCKERIZE_VERSION.tar.gz \
    && tar -C /usr/local/bin -xzvf dockerize-alpine-linux-amd64-$DOCKERIZE_VERSION.tar.gz \
    && rm dockerize-alpine-linux-amd64-$DOCKERIZE_VERSION.tar.gz

RUN git clone --branch ${BRANCH} --recurse-submodules https://github.com/billsonnn/nitro-imager.git .
RUN git checkout $COMMIT
RUN yarn install
RUN yarn build
ENTRYPOINT ["node", "/app/dist/src/main.js"]
#ENTRYPOINT ["tail", "-f", "/dev/null"]