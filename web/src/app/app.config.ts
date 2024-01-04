import {ApplicationConfig} from '@angular/core';
import {provideRouter} from '@angular/router';
import {routes} from './app.routes';
import {provideClientHydration} from '@angular/platform-browser';
import {provideHttpClient, withInterceptors} from "@angular/common/http";
import {withCredentialsInterceptor} from "./interceptors/with-credentials.interceptor";

export let appConfig: ApplicationConfig;
appConfig = {
  providers: [
    provideRouter(routes),
    provideClientHydration(),
    provideHttpClient(withInterceptors([
      withCredentialsInterceptor
    ]))
  ]
};
