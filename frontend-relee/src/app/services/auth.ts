import { Injectable } from '@angular/core';
import { HttpClient, HttpHeaders } from '@angular/common/http'; // ✅ <--- aquí está la corrección
import { Observable } from 'rxjs';

@Injectable({
  providedIn: 'root'
})
export class Auth {
  private baseUrl = 'http://relee.local/';
private apiRegister = this.baseUrl + 'register.php';
private apiLogin = this.baseUrl + 'login.php';


  constructor(private http: HttpClient) {}

  // ✅ Registro
  registrarUsuario(datos: any): Observable<any> {
    const headers = new HttpHeaders({ 'Content-Type': 'application/json' });
    return this.http.post<any>(this.apiRegister, datos, { headers });
  }

  // ✅ Login
  loginUsuario(datos: any): Observable<any> {
    const headers = new HttpHeaders({ 'Content-Type': 'application/json' });
    return this.http.post<any>(this.apiLogin, datos, { headers });
  }
}
