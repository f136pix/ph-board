import {Injectable} from '@angular/core';
import {HTTP_INTERCEPTORS, HttpClient} from "@angular/common/http";
import {Observable} from "rxjs";
import {User} from "../interfaces/user";

@Injectable({
  providedIn: 'root',
})
export class AuthService {

  constructor(private http: HttpClient) {
    this.http = http;
  }

  // login method
  public login(email: string, password: string): Observable<any> {
    return this.http.post(`http://localhost:8000/api/login`,
      {
        email: email,
        password: password
      }
    )
  }

  public register(email: string, name: string, surname: string, password: string, passwordConfirm: string): Observable<any> {
    return this.http.post(`http://localhost:8000/api/register`,
      {
        email: email,
        name: name,
        surname: surname,
        password: password,
        password_confirm: passwordConfirm
      })
  }

  public currentUser(): Observable<User> {
    return this.http
      .get<User>('http://localhost:8000/api/user',
      );
  }

  public logout(): Observable<void> {
    return this.http
      .post<void>('http://localhost:8000/api/logout',
        {},
      );
  }

  public updateProfile(data: object): Observable<Object> {
    return this.http.put<Object>('http://localhost:8000/api/users/info',
      data,
    )
  }

  public updatePassword(data: object): Observable<Object> {
    return this.http.put<Object>('http://localhost:8000/api/users/password',
      data
    )
  }
}


