import { Injectable } from '@angular/core';
import { HttpClient, HttpHeaders } from '@angular/common/http';
import { Observable, BehaviorSubject } from 'rxjs';

@Injectable({
  providedIn: 'root'
})
export class Auth {
  private baseUrl = 'http://relee.local/';
  private apiRegister = this.baseUrl + 'register.php';
  private apiLogin = this.baseUrl + 'login.php';

  // 🔥 Estado del usuario en memoria (para actualizar dinámicamente)
  private usuarioNombreSubject = new BehaviorSubject<string | null>(this.obtenerUsuario());
  usuarioNombre$ = this.usuarioNombreSubject.asObservable();

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

  // ✅ Guardar el nombre del usuario (después de login o registro)
  guardarUsuario(nombre: string) {
    localStorage.setItem('usuario_nombre', nombre);
    this.usuarioNombreSubject.next(nombre);
  }

  // ✅ Cerrar sesión
  cerrarSesion() {
    localStorage.removeItem('usuario_nombre');
    this.usuarioNombreSubject.next(null);
  }

  // ✅ Obtener el usuario actual desde almacenamiento
  obtenerUsuario(): string | null {
    return localStorage.getItem('usuario_nombre');
  }
}
