import { NgModule } from '@angular/core';
import { RouterModule, Routes } from '@angular/router';
import { ClienteListComponent } from './components/cliente-list/cliente-list.component';
import { ClienteFormComponent } from './components/cliente-form/cliente-form.component';
import { BookListComponent } from './components/book-list/book-list.component';
import { OrderListComponent } from './components/order-list/order-list.component';
import { BookFormComponent } from './components/book-form/book-form.component';
import { OrderFormComponent } from './components/order-form/order-form.component';
import { HomeComponent } from './components/home/home.component';
import { LoginComponent } from './auth/login/login.component';
import { DashboardComponent } from './pages/dashboard/dashboard.component';
import { BranchesComponent } from './pages/branches/branches.component';

const routes: Routes = [
{ path: 'login', component: LoginComponent },
   { path: 'dashboard', component: DashboardComponent },
//  { path: 'products', loadChildren: () => import('./pages/products/products.module').then(m => m.ProductsModule) },
  { path: 'branches', component: BranchesComponent },
 // { path: 'genres', loadChildren: () => import('./pages/genres/genres.module').then(m => m.GenresModule) },
 // { path: 'styles', loadChildren: () => import('./pages/styles/styles.module').then(m => m.StylesModule) },
 // { path: 'categories', loadChildren: () => import('./pages/categories/categories.module').then(m => m.CategoriesModule) },

 { path: '', redirectTo: 'login',pathMatch: 'full' }
];

@NgModule({
  imports: [RouterModule.forRoot(routes)],
  exports: [RouterModule]
})
export class AppRoutingModule { }
