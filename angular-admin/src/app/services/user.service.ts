import {Injectable} from '@angular/core';
import {Observable} from "rxjs";
import {User} from "../interfaces/user";
import {HttpClient} from "@angular/common/http";

@Injectable({
  providedIn: 'root'
})
export class UserService {
  endpoint: String = 'http://localhost:8000/api';

  constructor(private http: HttpClient) {
    this.http = http
  }

  // retrive todos users
  public all(page: number): Observable<User[]> {
    return this.http.get<User[]>(`${this.endpoint}/users?page=${page}`)
  }
}
