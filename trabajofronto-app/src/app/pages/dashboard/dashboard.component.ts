import { Component, HostListener } from '@angular/core';
import { Router } from '@angular/router';

@Component({
  standalone: false,
  selector: 'app-dashboard',
  templateUrl: './dashboard.component.html',
  styleUrls: ['./dashboard.component.scss'],
})
export class DashboardComponent {
  isDesktop: boolean = window.innerWidth > 768;

  cards = [
    { title: 'Productos', route: '/products', icon: 'checkroom' },
    { title: 'Marcas', route: '/branches', icon: 'storefront' },
    { title: 'Géneros', route: '/genres', icon: 'wc' },
    { title: 'Estilos', route: '/styles', icon: 'style' },
    { title: 'Categorías', route: '/categories', icon: 'category' },
    { title: 'Reclamaciones', route: '/reclamaciones', icon: 'report_problem' },
    { title: 'Calificaciones', route: '/calificaciones', icon: 'star_rate' },
    { title: 'Órdenes', route: '/ordenes', icon: 'shopping_bag' },
  ];

  constructor(private router: Router) {}

  @HostListener('window:resize')
  onResize() {
    this.isDesktop = window.innerWidth > 768;
  }

  navigateTo(route: string) {
    this.router.navigate([route]);
  }

  logout() {
    localStorage.removeItem('token');
    localStorage.removeItem('tenant_id');
    this.router.navigate(['/login']);
  }
}
