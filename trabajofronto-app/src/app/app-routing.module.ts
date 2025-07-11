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
import { BranchCreateComponent } from './pages/branch-create/branch-create.component';
import { GenresComponent } from './pages/genres/genres.component';
import { GenresCreateComponent } from './pages/genres-create/genres-create.component';
import { StylesComponent } from './pages/styles/styles.component';
import { StyleCreateComponent } from './pages/style-create/style-create.component';
import { CategorysComponent } from './pages/categorys/categorys.component';
import { CategoryCreateComponent } from './pages/category-create/category-create.component';
import { ProductsComponent } from './pages/products/products.component';
import { ProductDetailService } from './services/product-detail.service';
import { ProductCreateComponent } from './pages/product-create/product-create.component';



const routes: Routes = [
  {
    path: '',
    component: DashboardComponent,
    children: [
      // { path: 'products', component: ProductsComponent },
      { path: 'branches', component: BranchesComponent },
      { path: 'branches/create', component: BranchCreateComponent },
      { path: 'branches/edit/:id', component: BranchCreateComponent },
      { path: 'genres', component: GenresComponent },
      { path: 'genres/create', component: GenresCreateComponent },
      { path: 'genres/edit/:id', component: GenresCreateComponent },
      { path: 'styles', component: StylesComponent },
      { path: 'styles/create', component: StyleCreateComponent },
      { path: 'styles/edit/:id', component: StyleCreateComponent },
      { path: 'categories', component: CategorysComponent },
      { path: 'categories/create', component: CategoryCreateComponent },
      { path: 'categories/edit/:id', component: CategoryCreateComponent },
      { path: 'products', component: ProductsComponent },
      { path: 'products/create', component: ProductCreateComponent },
      { path: 'products/edit/:id', component: ProductCreateComponent },
      // ... otras rutas internas
    ]
  },
  { path: 'login', component: LoginComponent },
  { path: '**', redirectTo: '' }
];

@NgModule({
  imports: [RouterModule.forRoot(routes)],
  exports: [RouterModule]
})
export class AppRoutingModule { }
