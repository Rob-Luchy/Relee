import { Injectable } from '@angular/core';
import { HttpClient, HttpHeaders } from '@angular/common/http';
import { Observable } from 'rxjs';

@Injectable({
  providedIn: 'root'
})
export class LibrosService {

  private apiUrl = 'http://localhost/backend-relee/libros.php';

  constructor(private http: HttpClient) {}

  registrarLibro(libro: any): Observable<any> {
    const headers = new HttpHeaders({ 'Content-Type': 'application/json' });
    return this.http.post<any>(this.apiUrl, libro, { headers });
  }

  obtenerLibros(idUsuario?: number): Observable<any> {
    let url = this.apiUrl;
    if (idUsuario) url += `?usuario=${idUsuario}`;
    return this.http.get<any>(url);
  }

  eliminarLibro(id: number): Observable<any> {
    return this.http.delete<any>(`${this.apiUrl}?id=${id}`);
  }
}
