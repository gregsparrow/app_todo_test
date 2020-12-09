FROM alpine:latest as storage
WORKDIR /storage
COPY . .

FROM nginx:alpine
COPY --from=storage /storage/docker/nginx/default.conf /etc/nginx/conf.d/default.conf