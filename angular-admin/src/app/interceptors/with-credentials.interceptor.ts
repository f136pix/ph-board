import {HttpHandlerFn, HttpInterceptorFn, HttpRequest} from '@angular/common/http';

// allow receber/ enviar cookies/ session atr
export const withCredentialsInterceptor: HttpInterceptorFn = (req: HttpRequest<any>, next: HttpHandlerFn) => {
  let finalReq: HttpRequest<any>;

  finalReq = req.clone({
    withCredentials: true
  })

  return next(finalReq);
};
