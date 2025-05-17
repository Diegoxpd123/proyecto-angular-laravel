import { NgModule } from '@angular/core';
import { RouterModule, Routes } from '@angular/router';
import { ClienteListComponent } from './components/cliente-list/cliente-list.component';
import { ClienteFormComponent } from './components/cliente-form/cliente-form.component';
import { BookListComponent } from './components/book-list/book-list.component';
import { OrderListComponent } from './components/order-list/order-list.component';
import { BookFormComponent } from './components/book-form/book-form.component';
import { OrderFormComponent } from './components/order-form/order-form.component';
import { HomeComponent } from './components/home/home.component';

const routes: Routes = [
{ path: 'inicio', component: HomeComponent },
 { path: 'orders', component: OrderListComponent },
 { path: 'orders/nuevo', component: OrderFormComponent },
 { path: 'orders/:id/editar', component: OrderFormComponent },
 { path: 'clientes', component: ClienteListComponent },
 { path: 'clientes/nuevo', component: ClienteFormComponent },
 { path: 'clientes/:id/editar', component: ClienteFormComponent },
 { path: 'books', component: BookListComponent },
 { path: 'books/nuevo', component: BookFormComponent },
 { path: 'books/:id/editar', component: BookFormComponent },
 { path: '', redirectTo: 'inicio',pathMatch: 'full' }
];

@NgModule({
  imports: [RouterModule.forRoot(routes)],
  exports: [RouterModule]
})
export class AppRoutingModule { }
