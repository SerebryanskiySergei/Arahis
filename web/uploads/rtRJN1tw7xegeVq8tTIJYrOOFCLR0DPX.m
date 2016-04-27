clear all;
close all;
format long; % сделать формат вывода чисел в виде 3,1415, а не 3,145151е+00

% simeventslib
% А - закон генерации новых сущностей
% D - закон, определяющий время обработки сущности на сервере
% с - емкость очереди/стека
% K - число одновременно обрабатываемых сущностей на сервере

T = 50.0;

new_seed = @() floor(rand()*2016);

          % mean_p  service time
params_min = [1       1];
params_max = [7       9];

mean_ps =       params_min(1) : 0.5 : params_max(1);
service_times = params_min(2) : 0.5 : params_max(2);

in_queue = zeros(length(service_times), length(mean_ps));
p_denied = zeros(length(service_times), length(mean_ps));
for i = 1:length(mean_ps)
    for j = 1:length(service_times)
        seed = new_seed();
        seed_gen = new_seed();
        seed_service = new_seed();
        
        mean_gen = mean_ps(i);
        mean_service = service_times(j);

        sim('server', []);
        
%       среднее количество заявок, стоящих в очереди; 
        n_queue = queue.signals.values;
        in_queue(j, i) = mean(n_queue);
        
%       вероятность отказа клиенту в обслуживании
        gen = generated.signals.values;
        arr = arrived.signals.values;
        p_denied(j, i) = 1 - double(arr(end)) / double(gen(end));
    end
end

[X, Y] = meshgrid(service_times, mean_ps);
figure(1),
subplot(1,2,1),
surf(X, Y, in_queue'), 
xlabel('Service time mean'), ylabel('Generation mean'),
zlabel('Time'), title('Average time in queue'),
subplot(1,2,2),
contourf(X, Y, p_denied'), 
xlabel('Service time mean'), ylabel('Generation mean'),
zlabel('Probability'), title('Probability of being denied');
