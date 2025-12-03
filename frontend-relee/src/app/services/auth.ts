import { Injectable } from '@angular/core';
import { HttpClient, HttpHeaders } from '@angular/common/http';
import { Observable, BehaviorSubject } from 'rxjs';

@Injectable({
  providedIn: 'root'
})
export class Auth {
  // ✔ Backend en XAMPP
private baseUrl = 'http://relee.local/backend-relee/';
private apiRegister = this.baseUrl + 'register.php';
private apiLogin = this.baseUrl + 'login.php';



  private usuarioNombreSubject = new BehaviorSubject<string | null>(this.obtenerUsuario());
  usuarioNombre$ = this.usuarioNombreSubject.asObservable();

  constructor(private http: HttpClient) {}

  registrarUsuario(datos: any): Observable<any> {
    const headers = new HttpHeaders({ 'Content-Type': 'application/json' });
    return this.http.post<any>(this.apiRegister, datos, { headers });
  }

  loginUsuario(datos: any): Observable<any> {
    const headers = new HttpHeaders({ 'Content-Type': 'application/json' });
    return this.http.post<any>(this.apiLogin, datos, { headers });
  }

  guardarUsuario(nombre: string) {
    localStorage.setItem('usuario_nombre', nombre);
    this.usuarioNombreSubject.next(nombre);
  }

  cerrarSesion() {
    localStorage.removeItem('usuario_nombre');
    this.usuarioNombreSubject.next(null);
  }

  obtenerUsuario(): string | null {
    return localStorage.getItem('usuario_nombre');
  }
}
