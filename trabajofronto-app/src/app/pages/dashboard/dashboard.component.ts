import { Component } from '@angular/core';
import { Router } from '@angular/router';

@Component({
  standalone: false,
  selector: 'app-dashboard',
  templateUrl: './dashboard.component.html',
  styleUrls: ['./dashboard.component.scss'],
})
export class DashboardComponent {
  cards = [
    { title: 'Productos', route: '/products', icon: 'inventory' },
    { title: 'Marcas', route: '/branches', icon: 'store' },
    { title: 'Géneros', route: '/genres', icon: 'category' },
    { title: 'Estilos', route: '/styles', icon: 'style' },
    { title: 'Categorías', route: '/categories', icon: 'view_list' },
  ];

  constructor(private router: Router) { }

  navigateTo(route: string) {
    this.router.navigate([route]);
  }

  logout() {
    localStorage.removeItem('token');
    localStorage.removeItem('tenant_id');
    this.router.navigate(['/login']);
  }
}
