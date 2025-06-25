import { TestBed } from '@angular/core/testing';

import { UsuariotenantService } from './usuariotenant.service';

describe('UsuariotenantService', () => {
  let service: UsuariotenantService;

  beforeEach(() => {
    TestBed.configureTestingModule({});
    service = TestBed.inject(UsuariotenantService);
  });

  it('should be created', () => {
    expect(service).toBeTruthy();
  });
});
