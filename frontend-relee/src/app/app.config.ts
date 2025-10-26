import { ApplicationConfig, importProvidersFrom } from '@angular/core';
import { provideRouter, Routes } from '@angular/router';
import { Inicio } from './pages/inicio/inicio';
import { Libros } from './pages/libros/libros';
import { Login } from './pages/login/login';
import { Registro } from './pages/registro/registro';
import { Dashboard } from './pages/dashboard/dashboard';
import { HttpClientModule } from '@angular/common/http';
import { ReactiveFormsModule } from '@angular/forms';

const routes: Routes = [
  { path: '', component: Inicio },
  { path: 'inicio', component: Inicio },
  { path: 'libros', component: Libros },
  { path: 'login', component: Login },
  { path: 'registro', component: Registro },
  { path: 'dashboard', component: Dashboard }, // 👈 nueva ruta
  { path: '**', redirectTo: 'inicio' }
];


export const appConfig: ApplicationConfig = {
  providers: [
    provideRouter(routes),
    importProvidersFrom(HttpClientModule, ReactiveFormsModule)
  ]
};
