import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { Observable } from 'rxjs';

@Injectable({
  providedIn: 'root'
})
export class Auth {
  private apiUrl = 'http://localhost/backend-relee/register.php';

  constructor(private http: HttpClient) {}

  registrarUsuario(datos: any): Observable<any> {
    return this.http.post<any>(this.apiUrl, datos);
  }
}
